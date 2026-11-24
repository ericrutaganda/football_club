<?php
declare(strict_types=1);
class TrainingController extends Controller {
    private Training $model;
    public function __construct(){ $this->model=new Training(); }
    public function index(): void{require_login();$this->view('trainings/index',['title'=>'Training','trainings'=>$this->model->all()]);}
    public function form(): void{
        require_login();
        $id=request_id($_GET,'id');
        if($id===false){http_response_code(400);exit('Invalid training session ID.');}
        $id=$id??0;
        $training=$id?$this->model->find($id):null;
        if($id&&!$training){http_response_code(404);exit('Training session not found.');}
        $this->view('trainings/form',['title'=>$id?'Edit training':'Add training','training'=>$training]);
    }
    public function save(): void{
        require_login();verify_csrf();
        $date=parse_datetime_local(post('training_date'));
        $d=['title'=>post('title'),'training_date'=>$date,'location'=>post('location'),'coach'=>post('coach'),'notes'=>post('notes')];
        $id=request_id($_POST,'id');
        if($d['title']===''||$date===null||$d['location']===''||$id===false){
            flash('error','Enter a title, a valid date and time, and a location.');
            redirect('index.php?route=trainings/form'.(is_int($id)?'&id='.$id:''));
        }
        if($id!==null&&!$this->model->find($id)){http_response_code(404);exit('Training session not found.');}
        $this->model->save($d,$id);flash('success','Training saved.');redirect('index.php?route=trainings');
    }
    public function delete(): void{
        require_login();verify_csrf();
        $id=request_id($_POST,'id');
        if($id===null||$id===false){http_response_code(400);exit('Invalid training session ID.');}
        if(!$this->model->find($id)){http_response_code(404);exit('Training session not found.');}
        $this->model->delete($id);flash('success','Training deleted.');redirect('index.php?route=trainings');
    }
}
