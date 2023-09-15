<?php

/**
 * Zoom Meeting Manager Library
 */
class Zoom_Meeting_Manager
{
    const API_URL = 'https://api.zoom.us/v2/';

    const API_OAUTH_URL = 'https://zoom.us/oauth/authorize';

    const API_OAUTH_TOKEN_URL = 'https://zoom.us/oauth/token';

    const API_TOKEN_EXCHANGE_URL = 'https://zoom.us/oauth/token';

    private $_appId;

    private $_appSecret;

    private $_redirectUri;

    private $_accesstoken;

    private $_timeout = 90000;

    private $_connectTimeout = 20000;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_appId = get_option('zmm_app_id');
        $this->_appSecret = get_option('zmm_app_secret');
        $this->_redirectUri = get_option('zmm_app_redirect_uri');

        if ($this->_appId == '' || $this->_appSecret == '') {
            echo('<h1 style="text-align:center;margin-top:50px;">Configuration data is missing.<br><br> Navigate in <a href="' . admin_url("settings?group=zoom-meeting-manager-settings") . '">Settings->Zoom Meeting Manager</a> to add your Zoom APP ID and APP SECRET</h1>');
            echo '<h3><a href="' . admin_url('settings?group=zoom-meeting-manager-settings') . '" style="margin-left:50%;">[ Zoom Settings ] </a></h3>';
            die;
        }

        $this->setAppId($this->_appId);
        $this->setAppSecret($this->_appSecret);
        $this->setRedirectUri($this->_redirectUri);
        $this->setAccessToken($this->_DBGetUserAccessToken());

        if (isset($this->_timeout)) {
            $this->setTimeout($this->_timeout);
        }

        if (isset($this->_connectionTimeout)) {
            $this->setConnectTimeout($this->_connectionTimeout);
        }
    }


    /**
     * Get and generate login url for ZOOM
     *
     * @param string $state
     *
     * @return void
     */
    public function getLoginUrl($state = '')
    {
        return self::API_OAUTH_URL . '?client_id=' . $this->getAppId() . '&redirect_uri=' . urlencode($this->getRedirectUri()) .
            '&response_type=code' . ($state != '' ? '&state=' . $state : '');

        throw new ZoomException("Error: getLoginUrl()");
    }

    /**
     * Fetch all user meetings
     * All upcomning meetings including live
     * This includes all valid past meetings (unexpired), live meetings and upcoming scheduled meetings. It is equivalent to the combined list of “Previous Meetings” and “Upcoming Meetings” displayed in the user’s
     *
     * @param string  $type
     * @param integer $page_size
     * @param integer $page_number
     *
     * @return mixed
     */
    public function getUserMeetings($type = 'all', $page_size = 30, $page_number = 1)
    {
        $result = $this->_makeCall('users/me/meetings', compact('type', 'page_size', 'page_number'));

        if (isset($result) && isset($result->total_records) && $result->total_records > 0) {
            if ($result) {
                foreach ($result->meetings as $meeting) {
                    $meeting->web_url = str_replace('j/', 'wc/join/', $meeting->join_url);
                }
            }
            return $result;
        }
        return 'unauthenticated';
    }

    /**
     * Get personal data for zoom
     *
     * @param string $type
     *
     * @return mixed
     */
    public function me($type = 'live')
    {
        return $this->_makeCall('users/me', compact('type'));
    }

    /**
     * Fetch user webinars
     *
     * @param string  $id
     * @param integer $page_size
     * @param integer $page_number
     *
     * @return mixed
     */
    public function getUserWebinars($id, $page_size = 30, $page_number = 1)
    {
        return $this->_makeCall('users/' . $id . '/webinars', compact('page_size', 'page_number'));
    }


    /**
     * Fetches autht token
     *
     * @param string $code
     *
     * @return void
     */
    public function getOAuthToken($code)
    {
        $apiData = [
            'grant_type'   => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
            'code'         => $code
        ];

        $authorization = base64_encode($this->getAppId() . ':' . $this->getAppSecret());
        $header = ['Authorization: Basic ' . $authorization];

        $result = $this->_makeOAuthCall(self::API_OAUTH_TOKEN_URL, $apiData, 'POST', $header);

        return $result;
    }


    /**
     * Refreshesh current zoom token
     *
     * @param string $refreshToken
     *
     * @return object
     */
    public function refreshToken($refreshToken)
    {
        $apiData = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        $authorization = base64_encode($this->getAppId() . ':' . $this->getAppSecret());
        $header = ['Authorization: Basic ' . $authorization];

        $result = $this->_makeOAuthCall(self::API_OAUTH_TOKEN_URL, $apiData, 'POST', $header);

        $this->_DBUpdateUserAccessToken($result);

        return $result;
    }


    /**
     * Function to access zoom API
     *
     * @param string  $function
     * @param array   $params
     * @param string  $method
     * @param boolean $createMeeting
     *
     * @return
     */
    protected function _makeCall($function, $params = null, $method = 'GET', $createMeeting = false)
    {
        // if (!isset($this->_accesstoken)) {
        //      throw new ZoomException("Error: _makeCall() | $function - This method requires an authenticated users access token.");
        // }

        $paramString = null;

        if (isset($params) && is_array($params)) {
            $paramString = '?' . http_build_query($params);
        }

        $apiCall = self::API_URL . $function . (('GET' === $method) ? $paramString : null);

        $headerData = [
            'Authorization: Bearer ' . $this->getAccessToken(),
        ];

        if ($createMeeting) {
            $headerData[] = "Content-Type: application/json";
        } else {
            $headerData[] = "Accept: application/json";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->_connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        // Create meeting fields
        if ($createMeeting) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        // Delete meeting custom curl request
        if ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        // false SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $jsonData = curl_exec($ch);

        if (!$jsonData) {
            throw new ZoomException('Error: _makeCall() - cURL error: ' . curl_error($ch), curl_errno($ch));
        }

        list($headerContent, $jsonData) = explode("\r\n\r\n", $jsonData, 2);

        curl_close($ch);

        return json_decode($jsonData);
    }


    /**
     * Main function for making authentication to Zoom
     *
     * @param string $apiHost
     * @param array  $params
     * @param string $method
     * @param array  $header
     *
     * @return json
     */
    private function _makeOAuthCall($apiHost, $params, $method = 'POST', $header = [])
    {
        $paramString = null;

        if (isset($params) && is_array($params)) {
            $paramString = '?' . http_build_query($params);
        }

        $apiCall = $apiHost . (('GET' === $method) ? $paramString : null);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($header, ['Accept: application/json']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_timeout);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $jsonData = curl_exec($ch);

        if (!$jsonData) {
            throw new ZoomException('Error: _makeOAuthCall() - cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($jsonData);
    }


    /**
     * Helper function to get user access token
     *
     * @return string
     */
    public function _DBGetUserAccessToken()
    {
        $result = get_instance()->db->query("SELECT access_token FROM  " . db_prefix() . "zmm")->row();

        if ($result !== null) {
            return $result->access_token;
        }
    }

    /**
     * Helper function for refreshing the access token
     *
     * @return string
     */
    public function _DBGetUserRefreshToken()
    {
        $result = get_instance()->db->query("SELECT refresh_token FROM  " . db_prefix() . "zmm")->row();

        if ($result !== null) {
            return $result->refresh_token;
        }
    }

    /**
     * Helper function for access tokens update in database
     *
     * @return void
     * @var object $response
     */
    public function _DBUpdateUserAccessToken($response)
    {
        return get_instance()->db->update(db_prefix() . 'zmm', $this->_DBHandleTokenData($response));
    }

    /**
     * Helper function for access tokens, saved in database
     *
     * @return void
     * @var object $response
     */
    public function _DBInsertUserAccessToken($response)
    {
        get_instance()->db->insert(db_prefix() . 'zmm', $this->_DBHandleTokenData($response));
    }


    /**
     * Check if token data is valid
     *
     * @return void
     * @var object $data
     */
    private function _DBHandleTokenData($data)
    {
        if (isset($data->error) && $data->error == 'invalid_client') {
            die('<h3 style="text-align:center;margin-top:30px;">Your current Zoom APP ID or APP SECERT is not inserted correctly or it is empty in your configuration located in: <a href="' . admin_url("settings?group=zoom-meeting-manager-settings") . '">Settings->Zoom Meeting Manager</a></h3>');
        }

        if (isset($data->error) && $data->error == 'invalid_request') {
            get_instance()->db->empty_table(ZMM_TABLE_ZOOM);
            redirect('admin/zoom_meeting_manager/index');
        }

        $this->setAccessToken($data->access_token);

        return [
            'user_id'       => get_staff_user_id(),
            'access_token'  => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_in'    => time() + $data->expires_in
        ];
    }

    /**
     * Check if user is authenticated with zoom
     *
     * @return boolean
     */
    public function isAuth()
    {
        $CI = &get_instance();

        $account = $CI->db->get(db_prefix() . 'zmm')->row();

        if (is_object($account)) {
            if ($account->expires_in <= time() + 300) {
                if ($this->refreshToken($account->refresh_token)) {
                    redirect('admin/zoom_meeting_manager/index');
                } else {
                    throw new ZoomException('Could not refresh the token: function isAuth()');
                }
            } else {
                return true;
            }
        }
        return false;
    }


    /**
     * Main function for creating new meeting on zoom
     *
     * @param array $meetingData
     *
     * @return void
     */
    public function createMeeting($meetingData)
    {
        $json = $this->readyJsonData($meetingData);
        $participants = $json['participants'];
        unset($json['participants']);

        $emailFields = [];

        try {
            $data = $this->_makeCall('users/me/meetings', $json, 'POST', true);

            if (isset($data->code) && $data->code == 124) {
                $this->revalidateToken();
            } else {
                $emailFields[] = $data;

                $emailFields[0]->web_url = str_replace('j/', 'wc/join/', $data->join_url);

                $emailFields[0]->form = $json;

                /**
                 * Add the participants to the meeting table in case user uses Free account
                 */
                $this->addParticipantsToMeetingTable($participants, $data->id);

                foreach ($participants as $participant) {

                    if ($this->me()->type > 1) {
                        /**
                         * This is paid users feature
                         * 1 - Free
                         * 2 - Licenced
                         * 3 - On-perm
                         */
                        $this->addRegistrants($emailFields[0], $participant[0]);
                    }
                    send_mail_template('zoom_meeting_manager_meeting_created_to_participants', 'zoom_meeting_manager', $emailFields[0], array_to_object($participant[0]));
                }
                /**
                 * Create note in database for created meeting
                 */
                $this->_initMeetingNotes($data->id);
            }
            redirect('admin/zoom_meeting_manager/index');
        } catch (Exception $e) {
            $this->revalidateToken();
            $this->createMeeting($meetingData);
        }
    }


    /**
     * Insert new meeting notes
     *
     * @param string $meeting_id
     *
     * @return void
     */
    private function _initMeetingNotes($meeting_id)
    {
        $data = [
            'meeting_id' => $meeting_id,
        ];

        $CI = &get_instance();
        $CI->db->insert(ZMM_TABLE_NOTES, $data);
    }


    /**
     * Add meeting registrants
     * This is for paid accounts
     *
     * @param objecvt $emailFields
     * @param object  $participant
     *
     * @return void
     */
    private function addRegistrants($emailFields, $participant)
    {
        $registrant = [
            'email'      => $participant->email,
            'first_name' => $participant->firstname,
            'last_name'  => $participant->lastname
        ];
        /**
         * This is paid users feature
         */
        $this->_makeCall('meetings/' . $emailFields->id . '/registrants', array_to_object($registrant), 'POST', true);
    }


    /**
     * Delete meeting and its data
     *
     * @param string $id
     *
     * @return boolean|exception
     */
    public function deleteMeeting($id)
    {
        $CI = &get_instance();
        $CI->db->where('meeting_id', $id);
        $CI->db->delete(ZMM_TABLE_PARTICIPANTS);

        /**
         * Also delete Notes
         */
        $CI->db->where('meeting_id', $id);
        $CI->db->delete(ZMM_TABLE_NOTES);

        try {
            return $this->_makeCall('/meetings/' . $id, null, 'DELETE');
        } catch (ZoomException $e) {
            if ($e->getCode() == 1001 || $e->getCode() == 3001) {
                return false;
            }
        }
    }


    /**
     * Get meeting
     *
     * @param string $id
     *
     * @return boolean|exception
     */
    public function getMeeting($id)
    {
        try {
            return $this->_makeCall('/meetings/' . $id);
        } catch (ZoomException $e) {
            if ($e->getCode() == 1001 || $e->getCode() == 3001) {
                return false;
            }
        }
    }


    /**
     * Get ready data as json and return back
     *
     * @param array $data
     *
     * @return array
     */
    private function readyJsonData($data)
    {
        $participants = [];

        if (isset($data['staff'])) {
            foreach ($data['staff'] as $staff) {
                $participants['staff'][] = zmm_get_user_limited_details($staff, 'staff');
            }
        }
        if (isset($data['leads'])) {
            foreach ($data['leads'] as $lead_id) {
                $participants['leads'][] = zmm_get_user_limited_details($lead_id, 'leads');
            }
        }
        if (isset($data['contacts'])) {
            foreach ($data['contacts'] as $contact_id) {
                $participants['contacts'][] = zmm_get_user_limited_details($contact_id, 'contacts');
            }
        }


        $data['date'] = to_sql_date($data['date'], true);

        $data['date'] = date("Y-m-d\TH:i:s", strtotime($data['date']));

        $meeting_data = [
            "topic"        => $data['topic'],
            "agenda"       => ($data['description']) ? $data['description'] : '',
            'participants' => isset($participants) ? $participants : [],
            "duration"     => $data['hour'] + $data['minutes'],
            "timezone"     => $data['timezone'],
            "start_time"   => $data['date'],
            "settings"     => [
                'join_before_host'  => isset($data['join_before_host']) ? true : false,
                'host_video'        => isset($data['host_video']) ? true : false,
                'participant_video' => isset($data['participant_video']) ? true : false,
                'mute_upon_entry'   => isset($data['mute_upon_entry']) ? true : false,
                'waiting_room'      => isset($data['waiting_room']) ? true : false,
            ]
        ];

        if (isset($participants) && !empty($participants)) {
            $meeting_data['settings'][] = [
                'approval_type'                  => 0,
                'registration_type'              => 1,
                'registrants_email_notification' => true
            ];
        }

        if (isset($data['password']) && $data['password'] != '') {
            $meeting_data['password'] = $data['password'];
        }

        return $meeting_data;
    }

    /**
     *
     * Revalidate refresh token
     *
     * @param object $e
     * @param array  $meetingData
     *
     * @return mixed
     *
     */
    public function revalidateToken($e = null)
    {
        if (($e !== null) && 401 == $e->getCode()) {
            $refresh_token = $this->_DBGetUserRefreshToken();

            $response = $this->refreshToken($refresh_token);

            $this->_DBUpdateUserAccessToken(json_decode($response->getBody()));
        }
    }


    /**
     * Add participants to meeting table in database
     *
     * @param array  $participants
     * @param string $meeting_id
     *
     * @return void
     */
    private function addParticipantsToMeetingTable($participants, $meeting_id)
    {
        (new ZoomParticipantsModel)->addParticipantsToMeetingTable($participants, $meeting_id);
    }


    /**
     * Setters and Getters
     */

    public function setAccessToken($token)
    {
        $this->_accesstoken = $token;
    }

    public function getAccessToken()
    {
        return $this->_accesstoken;
    }

    public function setAppId($appId)
    {
        $this->_appId = $appId;
    }

    public function getAppId()
    {
        return $this->_appId;
    }

    public function setAppSecret($appSecret)
    {
        $this->_appSecret = $appSecret;
    }

    public function getAppSecret()
    {
        return $this->_appSecret;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->_redirectUri = $redirectUri;
    }

    public function getRedirectUri()
    {
        return $this->_redirectUri;
    }

    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
    }

    public function setConnectTimeout($connectTimeout)
    {
        $this->_connectTimeout = $connectTimeout;
    }

}

/**
 * Error exception extenstion from CI
 */
class ZoomException extends \Exception
{
    // ..
}
