<?php
declare(strict_types=1);
class MatchController extends Controller {
    private MatchModel $model;
    public function __construct(){ $this->model=new MatchModel(); }
    public function index(): void{require_login();$this->view('matches/index',['title'=>'Matches','matches'=>$this->model->all()]);}
    public function form(): void{
        require_login();
        $id=request_id($_GET,'id');
        if($id===false){http_response_code(400);exit('Invalid match ID.');}
        $id=$id??0;
        $match=$id?$this->model->find($id):null;
        if($id&&!$match){http_response_code(404);exit('Match not found.');}
        $this->view('matches/form',['title'=>$id?'Edit match':'Schedule match','match'=>$match]);
    }
    public function save(): void{
        require_login();verify_csrf();
        $date=parse_datetime_local(post('match_date'));
        $ourScore=post('our_score');
        $opponentScore=post('opponent_score');
        $d=['opponent'=>post('opponent'),'match_date'=>$date,'venue'=>post('venue'),'competition'=>post('competition'),'our_score'=>$ourScore===''?null:filter_var($ourScore,FILTER_VALIDATE_INT),'opponent_score'=>$opponentScore===''?null:filter_var($opponentScore,FILTER_VALIDATE_INT),'status'=>post('status','scheduled')];
        $id=request_id($_POST,'id');
        if($d['opponent']===''||$date===null||$d['venue']===''||!in_array($d['status'],['scheduled','completed','cancelled'],true)||!valid_nonnegative_integer($ourScore)||!valid_nonnegative_integer($opponentScore)||(($ourScore==='')!==($opponentScore===''))||$id===false){
            flash('error','Enter valid match details. Both scores must be non-negative integers, or leave both blank.');
            redirect('index.php?route=matches/form'.(is_int($id)?'&id='.$id:''));
        }
        if($id!==null&&!$this->model->find($id)){http_response_code(404);exit('Match not found.');}
        $this->model->save($d,$id);flash('success','Match saved.');redirect('index.php?route=matches');
    }
    public function delete(): void{
        require_login();verify_csrf();
        $id=request_id($_POST,'id');
        if($id===null||$id===false){http_response_code(400);exit('Invalid match ID.');}
        if(!$this->model->find($id)){http_response_code(404);exit('Match not found.');}
        $this->model->delete($id);flash('success','Match deleted.');redirect('index.php?route=matches');
    }
}
