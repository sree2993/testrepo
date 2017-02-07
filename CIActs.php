<?php
//require_once("../mail/class.phpmailer.php");
require_once("../utility.php");
require_once("../model/CIActsM.php");
require_once("../model/CIIndustriesM.php");
require_once("../model/CICompanyTypeM.php");
require_once("../model/CIBusinessAreaM.php");
require_once("../model/CIStateM.php");
require_once("../views/CIActsView.php");
/*
Class name Standards

Controler : CCIxxxxxxCtrl.php
Model : CCIxxxxxM.php
View : CCIxxxxV.php
*/
define("LOGIN_DEFAULT",1);
class CIActs{
	private $cUsername;
	private $cPassword;
	private $cStatusObj;
	private $cHtmlObj;
	private $cDbObject;
	private $cformData;
	private $cActID;
	private $cActName;
	private $cActStateId;
	private $cStateid;
	public function __construct() 
    {
        // Register the Destructor to be called
        register_shutdown_function(array(&$this, "__destruct"));
        return ;
    }
	public function __destruct() 
    {
		//close the connection unset the variables.
		if($this->cDbObject){
			cilib_close_db_connection($this->cDbObject);
		}
		unset($cStatusObj);
	}
	public function Initialize( $pDebugTraceFlag = 0 )
    {
    	$this->cDbObject = cilib_get_db_connection();
	}
	public function Main(){
		$action = cilib_isset('action',LOGIN_DEFAULT);
		if($action != LOGIN_DEFAULT){
			if(strtolower($action)==strtolower("actslist")) $this->cGetActslist();
			if(strtolower($action)==strtolower("newactform")) $this->cGetActsnewform();
			if(strtolower($action)==strtolower("actsindustrylist")) $this->cGetAllactindustries();
			if(strtolower($action)==strtolower("actscomptypelist")) $this->cGetAllactcomptypeList();
			if(strtolower($action)==strtolower("actsstateslist")) $this->cGetAllactstatesList();
			if(strtolower($action)==strtolower("addacts")) $this->cAddact();
			if(strtolower($action)==strtolower("searchact")) $this->cSearchact();
			if(strtolower($action)==strtolower("getactdetails")) $this->cGetact();
			if(strtolower($action)==strtolower("getactstateactivity")) $this->cGetactStateactivity();
			if(strtolower($action)==strtolower("updateact")) $this->cUpdateact();
			if(strtolower($action)==strtolower("deleteact")) $this->cDeleteact();
      		if(strtolower($action)==strtolower("centralacts")) $this->cCentralActs();
      		if(strtolower($action)==strtolower("stateacts")) $this->cStateActs();
      		if(strtolower($action)==strtolower("getviewact")) $this->cViewact();
      		if(strtolower($action)==strtolower("deleteactstateedit")) $this->cDeleteactstate();

		}
	}
	// all the function in controller should start with small 'c'
	// all the function in controller should start with small 'm'
	// all the function in controller should start with small 'v'

	public function cGetActslist(){	
			$actsmodel = new CIActsM();
			$actsmodel->mGetActslist($this->cStatusObj,$this->cDbObject);
			$businessareamodel = new CIBusinessAreaM();
			$businessareamodel->mBusinessAreaList($this->cStatusObj,$this->cDbObject);
			$statemodel = new CIStateM();
			$statemodel->mGetstatesList($this->cStatusObj,$this->cDbObject);
			$userview = new CIActsView();
			$userview->vActsHTML($this->cHtmlObj,$this->cStatusObj);
			echo $this->cHtmlObj['actshtml'];
	}
	public function cGetActsnewform(){	
			$actsmodel = new CIActsM();
			$actsmodel->mGetActslist($this->cStatusObj,$this->cDbObject);
			$businessareamodel = new CIBusinessAreaM();
			$businessareamodel->mBusinessAreaList($this->cStatusObj,$this->cDbObject);
			$statemodel = new CIStateM();
			$statemodel->mGetstatesList($this->cStatusObj,$this->cDbObject);
			$userview = new CIActsView();
			$userview->vnewActform($this->cHtmlObj,$this->cStatusObj);
			echo $this->cHtmlObj['actshtml'];
	}
	public function cGetAllactindustries(){
		$industrymodel = new CIIndustriesM();
		$industrymodel->mIndustriesList($this->cStatusObj,$this->cDbObject);
		//log_message("INFO","Json object all industries==>".json_encode($this->cStatusObj));
		echo json_encode($this->cStatusObj['industrieslist']);
	}
	public function cGetAllactcomptypeList(){
		$comptypemodel = new CICompanyTypeM();
		$comptypemodel->mGetCompTypeList($this->cStatusObj,$this->cDbObject);
		log_message("INFO","Json object all Company type list==>".json_encode($this->cStatusObj));
		echo json_encode($this->cStatusObj['comptypelist']);
	}
	public function cGetAllactstatesList(){
		$statemodel = new CIStateM();
		$statemodel->mGetstatesList($this->cStatusObj,$this->cDbObject);
		log_message("INFO","Json object all States list==>".json_encode($this->cStatusObj));
		echo json_encode($this->cStatusObj['statelist']);
	}
	public function cAddact(){
		$this->cActName = cilib_isset('actname',0);
		if($this->cActName){
			$actsmodel = new CIActsM();
			$actsmodel->mAddact($this->cStatusObj,$this->cDbObject);
		}
		log_message("INFO","cAddact=====>>>>>".json_encode($_POST));
	}
	public function cSearchact(){
		//$this->cstateId = cilib_isset('searchstateval',0);
		$actmodel = new CIActsM();
		$actmodel->mSearchact($this->cStatusObj,$this->cDbObject);
		echo json_encode($this->cStatusObj['actsearch']);
		//echo json_encode($this->cStatusObj['']);
	}
	public function cGetact(){
		$this->cActID = cilib_isset('actid',0);
		if($this->cActID){
			$actmodel = new CIActsM();
			$actmodel->mGetact($this->cStatusObj,$this->cDbObject);
			
			$actview = new CIActsView();
			$actview->vgetActsHTML($this->cHtmlObj,$this->cStatusObj);
			//echo $this->cHtmlObj['getactdata'];

			echo $this->cHtmlObj['getactdata'];
		}
	}
	public function cViewact(){
		$this->cActID = cilib_isset('actid',0);
		if($this->cActID){
			$actmodel = new CIActsM();
			$actmodel->mGetact($this->cStatusObj,$this->cDbObject);
			
			$actview = new CIActsView();
			$actview->vViewactHTML($this->cHtmlObj,$this->cStatusObj);
			//echo $this->cHtmlObj['getactdata'];

			echo $this->cHtmlObj['getactdata'];
		}
	}

	public function cGetactStateactivity(){
		$this->cActID = cilib_isset('actid',0);
		$this->cActStateId = cilib_isset('actstateid',0);
		$this->cStateid = cilib_isset('stateid',0);
		if($this->cActStateId){
			$actmodel = new CIActsM();
			$actmodel->mGetactStateactivity($this->cStatusObj,$this->cDbObject);
			echo json_encode($this->cStatusObj['getStateactivityinfo']);
		}

	}
	public function cUpdateact(){
		$actmodel = new CIActsM();
		$actmodel->mUpdateact($this->cStatusObj,$this->cDbObject);
	}
	public function cDeleteact(){
		$this->cActID = cilib_isset('actid',0);
	 if($this->cActID){
		$actmodel = new CIActsM();
		$actmodel->mDeleteact($this->cStatusObj,$this->cDbObject);
		echo $this->cStatusObj['deleteAct'];
	 }
	}
	public function cDeleteactstate(){
		$this->cActStateId = cilib_isset('actstateid',0);
	 if($this->cActStateId){
		$actmodel = new CIActsM();
		$actmodel->mDeleteactstate($this->cStatusObj,$this->cDbObject);
		echo $this->cStatusObj['deleteActstate'];
	 }
	}

  public function cCentralActs(){
                $actsmodel = new CIActsM();
	        $actsmodel->mGetCentralActslist($this->cStatusObj,$this->cDbObject);
		$userview = new CIActsView();
	        $userview->vActsCentralHTML($this->cHtmlObj,$this->cStatusObj);
	        echo $this->cHtmlObj['actshtml'];	
               // console.log("INFO","Json object acts status==>".json_encode($this->cStatusObj));     
                  if($this->cStatusObj['status']=="success"){
                      echo "success";
                  }else{
                      echo "not success";
                  }
  }
  public function cStateActs(){
                $actsmodel = new CIActsM();
	        $actsmodel->mGetStateActslist($this->cStatusObj,$this->cDbObject);
		$userview = new CIActsView();
	        $userview->vActsStateHTML($this->cHtmlObj,$this->cStatusObj);
	        echo $this->cHtmlObj['actshtml'];
                if($this->cStatusObj['status']=="success"){
                      echo "success";
                }else{
                      echo "not success";
                }
 }
	
}

// Class Object creation
	$controllerObj = new CIActs();
	try 
    {
        //initialize the controller object
        $controllerObj->Initialize(0);
    
        // execute Controller object
        $controllerObj->Main();
    
    } 
    catch (CCommonException $e) 
    {
        echo "<h1>Something wrong:</h1><br/>$e";
        exit(0);
    }

?>