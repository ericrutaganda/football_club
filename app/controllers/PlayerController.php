<?php
declare(strict_types=1);
class PlayerController extends Controller {
    private Player $model;
    public function __construct(){ $this->model=new Player(); }
    public function index(): void{require_login();$this->view('players/index',['title'=>'Players','players'=>$this->model->all()]);}
    public function form(): void{
        require_login();
        $id=request_id($_GET,'id');
        if($id===false){http_response_code(400);exit('Invalid player ID.');}
        $id=$id??0;
        $player=$id?$this->model->find($id):null;
        if($id&&!$player){http_response_code(404);exit('Player not found.');}
        $this->view('players/form',['title'=>$id?'Edit player':'Add player','player'=>$player]);
    }
    public function save(): void{
        require_login();verify_csrf();
        $jersey=post('jersey_no');
        $dob=post('dob');
        $d=['full_name'=>post('full_name'),'position'=>post('position'),'jersey_no'=>filter_var($jersey,FILTER_VALIDATE_INT),'dob'=>$dob,'phone'=>post('phone'),'status'=>post('status','active')];
        $id=request_id($_POST,'id');
        if($d['full_name']===''||!in_array($d['position'],['Goalkeeper','Defender','Midfielder','Forward'],true)||$d['jersey_no']===false||$d['jersey_no']<1||!valid_date($dob)||!in_array($d['status'],['active','injured','inactive'],true)||$id===false){
            flash('error','Enter valid player details, including a valid date, position, status and jersey number.');
            redirect('index.php?route=players/form'.(is_int($id)?'&id='.$id:''));
        }
        if($id!==null&&!$this->model->find($id)){http_response_code(404);exit('Player not found.');}
        $this->model->save($d,$id);flash('success','Player saved.');redirect('index.php?route=players');
    }
    public function delete(): void{
        require_login();verify_csrf();
        $id=request_id($_POST,'id');
        if($id===null||$id===false){http_response_code(400);exit('Invalid player ID.');}
        if(!$this->model->find($id)){http_response_code(404);exit('Player not found.');}
        $this->model->delete($id);flash('success','Player deleted.');redirect('index.php?route=players');
    }
}
