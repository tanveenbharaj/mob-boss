<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use Illuminate\Http\Request;
use App\Http\Controllers\Jira as Jira;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function index ($slug = "") {

        $mob = [];
        if(!empty($slug)) {
            $mob = Mob::where("slug", $slug)->firstOrFail()->toArray();
        }

        return view('mobBoard', $mob);
    }

    public function getJiraTaskName(Request $request) {
        $id = $request->taskId;
        $body=$request->body;
       $response = Jira::search( 'issue='.$id );
        if(isset($response->errorMessages)){
            return \Response::json($response);
        } else if(isset($response->issues )){
            $arr=$response->issues;
            return \Response::json(
                array(
                    'task_key' => $arr[0]->key,
                    'task_name' => $arr[0]->fields->summary,
                    'creation_date' => $arr[0]->fields->created,
                    'task_type' => 'JIRA'
                )
            );
        }
        return null;
     }

     public function postComments(Request $request){

         $id = $request->task_key;
         $comments=$request->comment_body;
         $response = Jira::addCommentToTask( $id,$comments );
         return  \Response::json($response);
    }

    function saveJiraConfiguration(Request $request){
        config(['jira.url' =>  $request->url ]);
        config(['jira.username' =>  $request->username ]);
       config(['jira.password' =>  $request->password ]);
        $fp = fopen(base_path() .'/config/jira.php' , 'w');
        fwrite($fp, '<?php return ' . var_export(config('jira'), true) . ';');
        fclose($fp);

        if(config('jira.url') == $request->url &&
            config('jira.username') == $request->username &&
            config('jira.password') == $request->password ){
            return "ok";
        }else{
            return "error";
        }
    }
     public function routeNotificationForSlack()
         {
             return $this->slack_webhook_url;
         }
}
