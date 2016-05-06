<?php

namespace App\Http\Controllers\News;

use App\Models\News, App\Models\User;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class NewsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
    }
    
    public function index()
    {
		$news = News::orderBy('id', 'desc')->take(10)->get()->toArray();
		$this->data['latest_news'] = $news;
		return view('news.list', $this->data);
	}

    public function show(Request $request, $year, $month, $date, $user, $title, $token = '')
    {
		$news_url = '/' . $request->path();
		$this->data['news'] = News::where('url', $news_url)->get();
		$this->data['user_info'] = User::find($this->data['news'][0]->created_by);
		
		return view('news.show', $this->data);
	}

    public function pdf(Request $request, $year, $month, $date, $user, $title, $token = '')
    {
		$news_url = '/' . substr($request->path(), 4);
		$this->data['news'] = News::where('url', $news_url)->get();
		$this->data['user_info'] = User::find($this->data['news'][0]->created_by)->toArray();
		
		$pdf = \App::make('dompdf.wrapper');
		return $pdf->loadView('news.pdf', $this->data)->download('news.pdf');
	}


    public function create(Request $request) 
    {
		$response['error'] = false;
		
		//validate title field
		if(trim($request->input('news_title')) == '') {
			
			$response['error'] = true;
			$response['message']['news_title'] = 'News title can\'t be empty.';
		}

		//validate content field
		if(trim($request->input('news_content')) == '') {
			
			$response['error'] = true;
			$response['message']['news_content'] = 'News content can\'t be empty.';
		} else {
			$stripped_content = preg_replace(array('/ {2,}/', '/[\t]/'), ' ', $request->input('news_content'));
			$stripped_content = preg_replace('/\n{3,}]/', '[BREAK]', $stripped_content);
			
			if(count(explode(' ', $stripped_content)) < 1){
				
				$response['error'] = true;
				$response['message']['news_content'] = 'You need atleast 500 words to publish a news.';
			}
		}
		
		//validate file upload
		if(!$request->hasFile('news_image')) {
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file';
		} else if($request->file('news_image')->getClientSize() > 1048576) {
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file of size 1 MB max.';
		} else if(!in_array($request->file('news_image')->guessClientExtension(), array('jpg','jpeg','png', 'gif'))) {
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file with one of the following extension: jpg, jpeg, png, gif';
		}
		
		//set error message form data
		if($response['error']) {
			
			Session::put('news_error' , $response);
			Session::put('form_data', $request->all());
		} else {
			
			$news_image = $request->file('news_image');
			$image_name = time() . '-' . sha1(Session::get('user.0.id')) . $news_image->getClientOriginalName();
			$news_image->move(public_path() . '/uploads/', $image_name);
			
			//file upload was successful, save new now
			if(file_exists(public_path() . '/uploads/' . $image_name)) {
			
				$news = new News();
				$news->title = htmlentities($request->input('news_title'));
				$news->image_path = '/uploads/' . $image_name;
				$news->content = htmlentities($stripped_content);
				$news->created_by = Session::get('user.0.id');
				$news->url = '/news/' . date('Y/m/d') . '/' . $news->created_by . '/' . 
						preg_replace('/[^0-9a-zA-Z_\s]/', '', $news->title) . '/' . rand(100000,999999);
				$news->url = str_replace(' ', '_', $news->url);
				$news->save();

				//remove error from session
				Session::forget('news_error');

				//file upload error show error message
				$response['error'] = false;
				$response['growl_notification'] = true;
				$response['message'] = 'News saved successfully.';
				Session::put('news_message' , $response);

			} else {
				
				//file upload error show error message
				$response['error'] = true;
				$response['growl_notification'] = true;
				$response['message']['news_image'][] = 'Error occured while upload news image. Please contact our support team.';
				Session::put('news_error' , $response);
			}
		}

		return redirect('/dashboard');
	}
}
