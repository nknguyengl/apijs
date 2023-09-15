<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends AdminController
{
	/**
	 * Zoom Library Instance
	 *
	 * @var Zoom_Meeting_Manager
	 */
	public $zoom;

	public function __construct()
	{
		parent::__construct();
		/**
		 * Init libraries and models
		 */
		$this->load->library('Zoom_Meeting_Manager');
		$this->load->model('ZoomMeetingManager', 'zoom_meeting_model');
		$this->load->model('ZoomParticipantsModel');
		$this->zoom = new Zoom_Meeting_Manager();

		if (!$this->zoom->isAuth()) {
			$this->zoom->revalidateToken();
		}
	}

	/**
	 * Main index where all meeting are shown
	 *
	 * @return view
	 */
	public function index()
	{

		if (!staff_can('view', 'zoom_meeting_manager')) {
			show_404();
		}

		$data = [
			'zoom' => $this->zoom,
			'live' => $this->zoom->getUserMeetings(),
			'user' => $this->zoom->me()
		];

		$this->load->view('index', $data);
	}

	/**
	 * Create meeting view
	 *
	 * @return view
	 */
	public function createMeeting()
	{
		if (!staff_can('create', 'zoom_meeting_manager')) {
			access_denied('Zoom Meeting Manager');
		}

		$data = [
			'staff_members' => $this->staff_model->get('', ['active' => 1]),
			'rel_type' => 'lead',
			'rel_contact_type' => 'contact',
			'rel_contact_id' => '',
			'rel_id' => '',
			'user' => $this->zoom->me()
		];

		$this->load->view('create', $data);
	}

	/**
	 * Create new meeting
	 *
	 * @return void
	 */
	public function create()
	{
		$data = $this->input->post();

		if ($data) {
			$this->zoom->createMeeting($data);
			zmm_redirect_after_event('success', _l('zmm_meeting_created'));
		}
	}

	/**
	 * View meeting
	 *
	 * @return void
	 */
	public function view()
	{
		if (!staff_can('view', 'zoom_meeting_manager')) {
			show_404();
		}

		$id = $this->input->get('mid');

		if ($id) {
			$data['id'] = $id;
		} else {
			show_404();
		}

		$this->load->view('view', $data);
	}

	/**
	 * Delete meeting
	 *
	 * @return void
	 */
	public function delete()
	{
		if (!staff_can('delete', 'zoom_meeting_manager')) {
			show_404();
		}

		$id = $this->input->get('mid');
		if ($id) {
			$this->zoom->deleteMeeting($id);
			zmm_redirect_after_event('success', _l('zmm_meeting_deleted'));
		}
	}

	/**
	 * Zoom Authentication Callback
	 *
	 * @return mixed
	 */
	public function zoom_callback()
	{
		try {
			$token = $this->zoom->getOAuthToken($this->input->get('code'));

			if (!$this->zoom->_DBGetUserAccessToken()) {
				$this->zoom->_DBInsertUserAccessToken($token);
			} else {
				$this->zoom->_DBUpdateUserAccessToken($token);
			}
			$this->zoom->setAccessToken($token);
			redirect('admin/zoom_meeting_manager/index');
		} catch (Exception $e) {

			if (401 == $e->getCode()) {
				$refresh_token = $this->zoom->refreshToken($this->zoom->_DBGetUserRefreshToken());
				if ($refresh_token) {
					$this->zoom->_DBUpdateUserAccessToken($refresh_token);
				}
			}
		}
	}

	/**
	 * Get meeting notes
	 *
	 * @param string $meeting_id
	 * @return json
	 */
	public function get_notes($meeting_id)
	{
		if (!staff_can('view', 'zoom_meeting_manager')) {
			show_404();
		}

		echo json_encode($this->zoom_meeting_model->get_meeting_notes($meeting_id));
	}


	/**
	 * Update meeting notes
	 *
	 * @param string $meeting_id
	 * @return json
	 */
	public function update_notes()
	{
		if (!staff_can('view', 'zoom_meeting_manager')) {
			show_404();
		}

		$request = $this->input->post();

		if ($request) {
			$data = [
				'meeting_id' => $request['meeting_id'],
				'note' => $request['notes']
			];
		}

		echo json_encode($this->zoom_meeting_model->update_meeting_notes($data));
	}
}
