<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
// use App\http\Requests\CallbackRequest;
use App\Models\User;
use App\Models\Callback;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id_role =Auth::user()->role ;
        if($id_role == 1){
            return view('allCallbacks');
        }else{
            return view('home');
        }
    }
    public function submitMassage(Request $req)
    {
		$rules = [
			'title_message' => 'required',
			'message' => 'required',
			'uploadFile' => 'required|file|mimes:jpeg,bmp,png,gif,docx,xlsx,pdf|max:3072'
		];

		$messages = [
			'title_message.required' => 'Поле Тема сообщения является обязательным',
			'message.required' => 'Поле Текст сообщения является обязательным',
			'uploadFile.required' => 'Поле Загрузить файл является обязательным',
			'uploadFile.mimes' => 'Поле загружаемого файла должно быть файлом типа:  jpg, jpeg, png, gif, docx, xlsx, pdff',
			'uploadFile.max' => 'Поле загружаемого файла должно быть меньше 3 МБ'
		];

$req->validate($rules,$messages);

        $filePath=null;
        $filePath2=null;
        if($req->hasFile('uploadFile')){
            $file = $req->file('uploadFile');
            $fileName = rand(1, 9999) . $file->getClientOriginalName();
            // $filePath_year =  date("Y") . '/' . date("m") . "/" . $fileName;
            $filePath_year =  $fileName;
            $filePath= $req->file('uploadFile')->storeAs('uploads',  $filePath_year);
        }
        $id_user =Auth::user()->id ;
        $callback = new callback();
        $callback->id_user = $id_user;
        $callback->title_message = $req->input('title_message');
        $callback->message = $req->input('message');
        $callback->uploadFile = "app/".$filePath;
        $callback->save();
        // $toEmail = "alnooras10@mail.ru";
        // $message = $req->input('message');
		// $mm = new SendMail($message);
		// Mail::to($toEmail)->send(new SendMail($message));
        return redirect()->route('home')->with('success', 'Сообщение было отправлено');
    }
    
    public function allCallbacks(){
        $id_role =Auth::user()->role ;
        if($id_role == 1){
            return view('allCallbacks');
        }else{
            return view('home');
        }
    }
    public function AjaxAllCallbacks(Request $request){
     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); 

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; 
     $columnName = $columnName_arr[$columnIndex]['data']; 
     $columnSortOrder = $order_arr[0]['dir']; 
     $searchValue = $search_arr['value']; 

     $totalRecords = callback::select('count(*) as allcount')->count();
     $totalRecordswithFilter = callback::select('count(*) as allcount')->where('title_message', 'like', '%' .$searchValue . '%')->count();

     $records = callback::orderBy($columnName,$columnSortOrder)
       ->where('callbacks.title_message', 'like', '%' .$searchValue . '%')
       ->where('callbacks.message', 'like', '%' .$searchValue . '%')
       ->where('callbacks.id_user', 'like', '%' .$searchValue . '%')
       ->where('callbacks.created_at', 'like', '%' .$searchValue . '%')
       ->select('callbacks.*')
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();
     
     foreach($records as $record){
        $DataUser = User::find($record->id_user);
        $time_createdUser =  $DataUser->created_at;
        $time_createdUser = date('d-m-Y', strtotime($time_createdUser));
        $username =  $DataUser->name;
        $useremail =  $DataUser->email;
        $id = $record->id;
        $id_user = $record->id_user;
        $title_message = $record->title_message;
        $created_atCB = $record->created_at;
        $created_atCB = date('d-m-Y', strtotime($record->created_at));
        $message = $record->message;
        $uploadFile = $record->uploadFile;
        $uploadFile = Storage::url($uploadFile);

        $data_arr[] = array(
          "id" => $id,
          "id_user" => $id_user,
          "time_createdUser" => $time_createdUser,
          "created_at" => $created_atCB,
          "username" => $username,
          "useremail" => $useremail,
          "title_message" => $title_message,
          "message" => $message,
          "uploadFile" => '<a href="'.url('/').$uploadFile.'" target="_blank">Ссылка файл</a>'
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
    }
}
