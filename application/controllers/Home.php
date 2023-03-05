<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	private $Auth;
	function __construct()
	{
		parent::__construct(); 
		$this->load->helper('rupiah');  //load helper rupiah
	}

	// token digunakan untuk javascript
	public function get_tokens($value="") {
		if ($this->session->userdata('bayand') == "SudahMasukMas") {
			echo $this->security->get_csrf_hash();
		}
	}

	// fun halaman
	public function index()
	{
		if($this->session->userdata('member_customer_012'))
				{
					$session_data = $this->session->userdata('member_customer_012');
					$this->Auth['user'] = $session_data['id'];
					$this->customer($this->Auth['user']);
				}else{
					$this->beranda();
				}
	}
    public function beranda(){
		$data['title'] = 'Home';
		$data['ten'] = $this->DButama->GetDB('tb_tentang')->row();  //load database
		$data['headerls'] = $this->DButama->GetDBWhere('tb_header_landscape', array('status' => 'Active'));  //load database
		$data['paket'] = $this->DButama->GetDB('tb_paket');  //load database
		$data['booking'] = $this->DButama->GetDBWhere('tb_booking', array('status' => 'Belum Selesai'));  //load database
		// fun view
		$this->load->view('utama/temp-header', $data);
		$this->load->view('utama/v_home');
		$this->load->view('utama/temp-footer');
	}
    public function customer($id=null){
		$data['title'] = 'Customer';
		$data['ten'] = $this->DButama->GetDB('tb_tentang')->row();  //load database
		$data['headerls'] = $this->DButama->GetDBWhere('tb_header_landscape', array('status' => 'Active'));  //load database
		$data['paket'] = $this->DButama->GetDB('tb_paket');  //load database
		$data['user'] = $this->DButama->GetDBWhere('tb_customer', array('id' => $id))->result();  //load database
		$data['booking'] = $this->DButama->GetDBWhere('tb_booking', array('cust_id' => $id));  //load database
		// fun view
		$this->load->view('utama/temp-header', $data);
		$this->load->view('utama/v_home');
		$this->load->view('utama/temp-footer');
	}
	public function v_login(){
		$data['title'] = 'Customer';
		$data['ten'] = $this->DButama->GetDB('tb_tentang')->row();  //load database
		$data['headerls'] = $this->DButama->GetDBWhere('tb_header_landscape', array('status' => 'Active'));  //load database
		$data['paket'] = $this->DButama->GetDB('tb_paket');  //load database
		// fun view
		$this->load->view('utama/temp-header', $data);
		$this->load->view('utama/v_login');
		$this->load->view('utama/temp-footer');
	}	
	// proses tambah
	public function proses()
	{
		//load form validasi
		$this->load->library('form_validation');
		// field form validasi
		$config = array(
			array('field' => 'id_paket','label' => "Paket",'rules' => 'required'),
			array('field' => 'nama','label' => "Nama Anda",'rules' => 'required'),
			array('field' => 'email','label' => 'Email Anda','rules' => 'required'),
			array('field' => 'no_hp','label' => 'No HP Anda','rules' => 'required|numeric'),
			array('field' => 'tgl_acara','label' => 'Tanggal Acara','rules' => 'required'),
			array('field' => 'alamat_tinggal','label' => 'Alamat Tempat Tinggal','rules' => 'required'),
			array('field' => 'alamat_acara','label' => 'Alamat Tempat Acara','rules' => 'required'),
			array('field' => 'dp','label' => 'Nominal DP','rules' => 'required'),
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			// menampilkan pesan error
			$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<strong>'.validation_errors().'</strong> 
							</div>');
			$this->_Values();
			redirect('home#booking','refresh');
		}else{			
			// Cek Master Paket
			$cek_paket = array('id' => $this->input->post('id_paket'));
			$stock_paket = 0;
			
			$paket = $this->DButama->GetDBWhere('tb_paket',$cek_paket);
			foreach ($paket->result() as $row){ $stock_paket= $row->stock;}
			// cek nama barang yang terdaftar
			$tgl_acara  = array('tgl_acara' => $this->input->post('tgl_acara'),'id_paket' => $this->input->post('id_paket'));
			$jumlah_acara = $this->DButama->GetDBWhere('tb_booking',$tgl_acara);
			$user_paket="";
			foreach ($jumlah_acara->result() as $row){ $user_paket= $row->cust_id;}
			if ($jumlah_acara->num_rows() == $stock_paket) {
				$this->_Values();
				// menampilkan pesan error
				$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Pilihan Paket Di Tanggal Acara Sudah Melebihi Pesanan Yang Kami sediakan..!</strong> 
						</div>');
				redirect('home#booking','refresh');
			}elseif ($user_paket == $this->input->get('id')){
				$this->_Values();
				// menampilkan pesan error
				$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Anda Sudah Melakukan Pemesanan..!</strong> 
						</div>');
				redirect('home#booking','refresh');
			}else{
				$data = array(
					'id_paket' => $this->input->post('id_paket'),
					'nama' => $this->input->post('nama'),
					'no_hp' => $this->input->post('no_hp'),
					'email' => $this->input->post('email'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'tgl_booking' => date('Y-m-d H:i:s'),
					'alamat_tinggal' => $this->input->post('alamat_tinggal'),
					'alamat_acara' => $this->input->post('alamat_acara'),
					'dp' => $this->input->post('dp'),
					'total' => $this->input->post('dp'),
					'status' => 'Belum Selesai',
					'cust_id' => $this->input->get('id'),
				);
				
				// upload bukti_transfer
				$bukti_transfer = $_FILES['bukti_transfer']['name'];
				if(!empty($bukti_transfer))
				{
					$upload = $this->_do_upload();
					$data['bukti_transfer'] = $upload;
				}

				// fun tambah
				$this->DButama->AddDB('tb_booking',$data);
				
				echo '<script language="javascript">';
				echo 'alert("Terimakasih Data Berhasil Dikirim")';  
				echo '</script>';
				redirect('home','refresh');
			}
		}
	}

	// proses upload bukti_transfer
	private function _do_upload()
	{
		$config['upload_path']   = 'assets/images/bukti-transfer/';  //lokasi folder
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name']  = TRUE;
        $config['file_name']     = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('bukti_transfer')) //upload and validate
        {
        	$data['inputerror'][] = 'bukti_transfer';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }

    // fun value validasi
    private function _Values()
	{
		$this->session->set_flashdata('nama', set_value('nama') );
		$this->session->set_flashdata('id_paket', set_value('id_paket') );
		$this->session->set_flashdata('email', set_value('email') );
		$this->session->set_flashdata('no_hp', set_value('no_hp') );
		$this->session->set_flashdata('tgl_acara', set_value('tgl_acara') );
		$this->session->set_flashdata('alamat_tinggal', set_value('alamat_tinggal') );
		$this->session->set_flashdata('alamat_acara', set_value('alamat_acara') );
	}
	// proses login
	public function login()
	{
		
			//load form validasi
			$this->load->library('form_validation');

			// field form validasi
			$config = array(
				array('field' => 'username','label' => "username",'rules' => 'required' ),
				array('field' => 'password','label' => 'Password','rules' => 'required',)
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == FALSE)
			{
				// menampilkan pesan error
				$this->session->set_flashdata('username', set_value('username') );
				$this->session->set_flashdata('password', set_value('password') );
				$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>'.validation_errors().'</strong> 
				</div>');
				redirect('Home', 'refresh');
			}else{
				// load databases dengan filter username
				$query = $this->DButama->GetDBWhere('tb_customer', array('username' => $this->input->post('username')));
				if ($query->num_rows() == 0 ) {
					$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Username / Password Tidak Ada</strong> 
						</div>');
						redirect('home/v_login', 'refresh');
				}else{
					$hasil = $query->row();
					if (password_verify($this->input->post('password'), $hasil->password)) {
					//if ($this->input->post('password')== $hasil->password) {
						foreach ($query->result() as $key ) {
							$sess_data['id'] = $key->id;
							$sess_data['nama'] = $key->nama;
							$sess_data['username'] = $key->username;
							$sess_data['email'] = $key->email;
							$this->session->set_userdata('member_customer_012',$sess_data);
							redirect('home#booking', 'refresh');
						}
					}else{
						// menampilkan pesan error
						$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<strong>Username / Password Tidak Ada</strong> 
							</div>');
							redirect('home/v_login', 'refresh');
					}
				}
			}
	}
	// fun proses daftar
	public function daftar()
	{
		//load form validasi
		$this->load->library('form_validation');
		// field form validasi
		$config = array(
			array('field' => 'email','label' => "email",'rules' => 'required' ),
			array('field' => 'password','label' => 'Password','rules' => 'required',)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			// menampilkan pesan error
			$this->session->set_flashdata('email', set_value('email') );
			$this->session->set_flashdata('password', set_value('password') );
			$this->session->set_flashdata('nama', set_value('nama') );
			$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>'.validation_errors().'</strong> 
			</div>');
			redirect('Home#booking', 'refresh');
		}else{
			if ($this->input->post()) {
				// cek username yang terdaftar
				$DataUser  = array('username' => $this->input->post('email'));
				if ($this->DButama->GetDBWhere('tb_customer',$DataUser)->num_rows() == 1) {
					// menampilkan pesan error
					$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Username Sudah ada..</strong> 
					</div>');
					redirect('Home#booking', 'refresh');
				}else{
					$pass=$this->input->post('password');
					$hash=password_hash($pass, PASSWORD_DEFAULT); //membuat encrypt password
					$data = array(
						'nama' => $this->input->post('nama'),
						'username' => $this->input->post('email'),
						'email' => $this->input->post('email'),
						'password' => $hash
					);
					// fungsi Cek Customer
					$sucsess = $this->DButama->AddDB('tb_customer',$data);
					if($sucsess){
						$query = $this->DButama->GetDBWhere('tb_customer', array('username' => $this->input->post('email')));
							if ($query->num_rows() == 0 ) {
								$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<strong>Gagal Membuat User...1</strong> 
									</div>');
									redirect('Home', 'refresh');
							}else{
								$hasil = $query->row();
								 if (password_verify($this->input->post('password'), $hasil->password)) {
								//if ($this->input->post('username')== $hasil->username) {
									foreach ($query->result() as $key ) {
										$sess_data['id'] = $key->id;
										$sess_data['nama'] = $key->nama;
										$sess_data['username'] = $key->username;
										$sess_data['email'] = $key->email;
										$this->session->set_userdata('member_customer_012', $sess_data);
										redirect('Home#booking', 'refresh');
									}
								}else{
									// menampilkan pesan error
									$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<strong>Gagal Membuat User...</strong> 
										</div>');
										redirect('Home', 'refresh');
								}
							}
					}else {
						$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<strong>Daftar Gagal..</strong> 
									</div>');
									redirect('Home', 'refresh');
						}
				}
			}
		}
	}
	// proses logout
	function logout()
	{
		$user_data = $this->session->all_userdata();
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}
		redirect('home','refresh');
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */