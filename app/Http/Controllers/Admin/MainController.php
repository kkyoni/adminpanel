<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReportUser;
use App\Models\PaymentHistory;
use App\Models\Gift;
use App\Models\Cms;
use Carbon\Carbon;
use Response;
class MainController extends Controller
{
    protected $authLayout = '';
    protected $pageLayout = 'admin.pages.';

    public function __construct()
    {
        $this->authLayout = 'admin.auth.';
        $this->pageLayout = 'admin.pages.';
        $this->middleware('auth');
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function Index Page
    -------------------------------------------------------------------------------------------- */


    public function index()
    {
        return view('front.auth.login');
    }

    /* -----------------------------------------------------------------------------------------
    @Description: Function Dashboard Page
    -------------------------------------------------------------------------------------------- */


    public function dashboard(){
      $cms_count = Cms::where('status','active')->count();
      $totalUsers = User::where('user_type','user')->count();
        return view('admin.pages.dashboard',compact('totalUsers','cms_count'));
    }

}
