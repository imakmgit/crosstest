<?php
/**
 * File contains news class to handle news related requests
 *
 * @category Controller
 * @package  NewsStand
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  Copyright
 * @link     
 */

namespace App\Http\Controllers\News;

use App\Models\News, App\Models\User;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

/**
 * Controller class to handle NewsStand news related requests
 *
 * @extends  Controller
 * @category NewsStand
 * @package  Product
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  COPYRIGHT
 * @link     
 */
class NewsController extends Controller
{
    /**
     * Create a news controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
    }
    
    /**
     * Shows home page of website with last 10 published  news
     *
     * @return object
     */
    public function index()
    {
		//get last 10 published news
		$news = News::with('newsCreator')->orderBy('id', 'desc')
					->take(10)->get()->toArray();

		//set data in view
		$this->data['latest_news'] = $news;
		$this->data['user_info'] = User::where('id', $news[0]['created_by'])
								->get()->toArray();
		
		return view('news.list', $this->data);
	}

    /**
     * Shows content of a specific news item
     *
     * @param object  $request
     * 
     * @return object
     */
    public function show(Request $request)
    {
		//get ulr of news
		$news_url = '/' . $request->path();
		
		//get news content from its url
		$this->data['news'] = News::where('url', $news_url)->get();
		
		//get user info who has published the news
		$this->data['user_info'] = User::find($this->data['news'][0]->created_by);
		
		return view('news.show', $this->data);
	}

    /**
     * Generates pdf of a specific news item
     *
     * @param object  $request
     * 
     * @return object
     */
    public function pdf(Request $request)
    {
		//get news url
		$news_url = '/' . substr($request->path(), 4);
		
		//get details of new and user who has created the news
		$this->data['news'] = News::where('url', $news_url)->get();
		$this->data['user_info'] = User::find($this->data['news'][0]->created_by)->toArray();
		
		//load pdf wrapper
		$pdf = \App::make('dompdf.wrapper');
		
		//send pdf data to browser
		return $pdf->loadView('news.pdf', $this->data)
					->download($this->data['news'][0]->title . '.pdf');
	}

    /**
     * Creates a news and save it in database
     *
     * @param object  $request
     * 
     * @return object
     */
    public function create(Request $request) 
    {
		$response['error'] = false;
		
		//validate title field
		if(trim($request->input('news_title')) == '') {
			
			$response['error'] = true;
			$response['message']['news_title'] = 'News title can\'t be empty.';
		} elseif(str_word_count($request->input('news_title')) > 20) {
			
			$response['error'] = true;
			$response['message']['news_title'] = 'Please limit news title to 20 words.';
		}

		//validate content field
		if(trim($request->input('news_content')) == '') {
			
			$response['error'] = true;
			$response['message']['news_content'] = 'News content can\'t be empty.';
		} elseif(str_word_count($request->input('news_content')) < 300){
				
			$response['error'] = true;
			$response['message']['news_content'] = 'You need atleast 300 words to publish a news.';
		}
		
		$valid_format = array('jpg','jpeg','png', 'gif');
		$extension = strtolower($request->file('news_image')->guessClientExtension());

		//validate file upload
		if(!$request->hasFile('news_image')) {
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file';
		} else if($request->file('news_image')->getClientSize() > 1048576) { //check 1 MB size limitation
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file of size 1 MB max.';
		} else if(!in_array($extension, $valid_format)) { //fiel format validation
			
			$response['error'] = true;
			$response['message']['news_image'] = 'Please upload a file with one of the following extension: jpg, jpeg, png, gif';
		}

		//set error message and form data in session in case of error
		if($response['error']) {
			
			Session::put('news_error', $response);
			Session::put('form_data', array(
				'news_title' => $request->input('news_title'),
				'news_content' => $request->input('news_content')
			));
		} else {
			
			//upload image and save
			$news_image = $request->file('news_image');
			$image_name = time() . '-' . sha1(Session::get('user.0.id')) . $news_image->getClientOriginalName();
			$news_image->move(public_path() . '/uploads/', $image_name);
			
			//file upload was successful, save news now
			if(file_exists(public_path() . '/uploads/' . $image_name)) {
			
				$news = new News();
				$news->title = htmlentities($request->input('news_title'));
				$news->image_path = '/uploads/' . $image_name;
				$news->content = htmlentities($request->input('news_content'));
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
				
				//file upload error, show error message
				$response['error'] = true;
				$response['growl_notification'] = true;
				$response['message']['news_image'][] = 'Error occured while uploading news image. ' .
														'Please contact our support team.';
				Session::put('news_error' , $response);
			}
		}

		return redirect('/dashboard');
	}
	
    /**
     * Show list of news created by user
     * 
     * @return object
     */
	public function news_list()
	{
		//get 10 news peer page created by user
		$news = News::where('created_by', Session::get('user.0.id'))
					->orderBy('id', 'desc')->paginate(10);
		
		//set data in view
		$this->data['published_news'] = $news;
		
		return view('news.user_published', $this->data);
	}
	
    /**
     * Delete a news created by user
     * 
     * @param object  $request
     * @param integer $id
     * 
     * @return object
     */
	public function delete(Request $request, $id) 
	{
		//if id is valid, process to delete news
		if(!empty($id)) {
			
			//get new info
			$news = News::find($id);
			
			//check if news with specified id is not found
			if(empty($news)) {
				
				$response['error'] = true;
				$response['message'] = 'Invalid data provided.';
			} else {
				
				//validate if news is created by logged in user,  delete news
				if($news->created_by == Session::get('user.0.id')) {
					
					//delete news
					$news->delete();
					
					$response['error'] = false;
					$response['message'] = 'News deleted successfully';
				} else { //if news is not created by logged in user, show error
					
					$response['error'] = true;
					$response['message'] = 'You do not have enough permission to delete this news.';
				}
			}
		} else {
			
			$response['error'] = true;
			$response['message'] = 'Invalid data provided.';
		}
		
		return response()->json($response);
	}
	
    /**
     * Shows rss feed of latest 10 news
     * 
     * @return object
     */
	public function rss()
	{
		$feed = new Feed();

		$channel = new Channel();
		$channel
			->title('NewsStand')
			->description('NewsStand provides latest news.')
			->url('http://www.newsstand.com')
			->language('en-US')
			->copyright('Copyright 2016, NewsStand')
			->pubDate(time())
			->lastBuildDate(time())
			->ttl(60)
			->appendTo($feed);

		//get last 10 published news
		$news = News::with('newsCreator')->orderBy('id', 'desc')
					->take(10)->get()->toArray();
		
		if(count($news)) {

			foreach($news as $news_item) {
				$item = new Item();
				$item
					->title($news_item['title'])
					->description($news_item['content'])
					->contentEncoded('<div><p>' . str_replace("\n", '</p><p>', $news_item['content']) . '</p></div>')
					->url(url($news_item['url']))
					->author($news_item['news_creator']['name'])
					->pubDate(strtotime($news_item['created_at']))
					->guid(url($news_item['url']), true)
					->appendTo($channel);
			}
		}
		echo $feed; // or echo $feed->render();
		
	}
}
