<?php
require_once("../mail/class.phpmailer.php");
require_once("../utility.php");
require_once("../model/CICompanySupervisorM.php");
require_once("../views/CIUserView.php");
//require_once ("../views/companyUserViews/supervisor/CICompanySupervisorView.php");
/*
Class name Standards

Controler : CCIxxxxxxCtrl.php
Model : CCIxxxxxM.php
View : CCIxxxxV.php
*/
define("LOGIN_DEFAULT",1);
class CICompanyUser{
	private $cCmpUserid;
	private $cCmpRoleid;
	private $cStatusObj;
	private $cHtmlObj;
	private $cDbObject;
        private $cEditorid;
        private $cLoc;
        private $cMonth;
        private $cYear;
       	private $cCmpID;
        private $cLocID;
        private $cfdate;
        private $cfmonth;
        private $ctdate;
        private $ctmonth;
        private $cfyear;
        private $ctyear;
        private $ccmpactyid;
        private $cActid;
        private $cregid;
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
                    if(strtolower($action)==strtolower("geteditor")) $this->cGetEditor();
                    if(strtolower($action)==strtolower("getEditorActivitypending")) $this->cGetEditorActivitypending();
                    if(strtolower($action)==strtolower("getsupervisorcmp")) $this->cGetSupervisorCMP();
                    if(strtolower($action)==strtolower("getEditorcmp")) $this->cGetEditorCMP();
                    if(strtolower($action)==strtolower("geteditorcmpLocationlist")) $this->cGetEditorCmpLocationList();

                    if(strtolower($action)==strtolower("geteditorloc")) $this->cGetEditorLoc();
                    if(strtolower($action)==strtolower("getsupactivity")) $this->cGetSupActivity();
                    if(strtolower($action)==strtolower("getsupdefaultactivity")) $this->cGetSupDefaultActivity();
                    if(strtolower($action)==strtolower("getsupeditordetails")) $this->cGetSupEditorDetails();
                    if(strtolower($action)==strtolower("geteditorCompany")) $this->cGetEditorCompany();
                    if(strtolower($action)==strtolower("geteditoractivity")) $this->cGetEditorActivity();
                    if(strtolower($action)==strtolower("getsupallactivity")) $this->cGetSupallActivity();
                    if(strtolower($action)==strtolower("rejactivity")) $this->cRejectActivity();
              	    if(strtolower($action)==strtolower("acceptactivity")) $this->cAcceptActivity();
                    if(strtolower($action)==strtolower("getcmpLocation")) $this->cgetcmpLocation();
                    
                    if(strtolower($action)==strtolower("getsupactivitydashboard")) $this->cGetSupActivityDashboard();
                    if(strtolower($action)==strtolower("getsupcmacts")) $this->cGetSupcmActs();
            
                    if(strtolower($action)==strtolower("fetchregdata")) $this->cFetchregdata();
                    if(strtolower($action)==strtolower("rejapproval")) $this->cRejapproval();
                    if(strtolower($action)==strtolower("acceptapproval")) $this->cAcceptapproval();   
                    if(strtolower($action)==strtolower("getsupeditorregcompleted")) $this->cGetSupEditorRegCompleted();
		}
	}
        public function cGetEditor(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mGetEditors($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['geteditors']);
            log_message("info","editormail===========>>".json_encode($this->cStatusObj));
       
        }

        public function cGetEditorActivitypending(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mGetEditorActivitypending($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['geteditors']);
            log_message("info","editormail===========>>".json_encode($this->cStatusObj));
        }

        public function cGetSupervisorCMP(){
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->mGetSupervisorCMP($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['getsupervisorcmp']);
        }
        public function cGetSupcmActs(){
        $this->cCmpID = cilib_isset('cmpid',0);
        $this->cCmpRoleid = cilib_isset('roleId',0);
        $this->cLocID = cilib_isset('locid',0);
        $this->cEditorID = cilib_isset('editor',0);     
        if( !empty($this->cCmpID) and $this->cCmpID != null ){
           $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
           $companiesmodel = new CICompanySupervisorM();
           $companiesmodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
           $companiesmodel->mGetSupcmActs( $this->cStatusObj,$this->cDbObject );
           //$_SESSION['companyuser']['companieslist']=$this->cStatusObj['getuserLocation'];            
           echo json_encode($this->cStatusObj['getcmacts']);
        }
    }
        public function cGetEditorCMP(){
             $this->cCmpRoleid = cilib_isset('roleID',0);
             $this->cEditorid = cilib_isset('editor',0);
             //$this->cCmpID = cilib_isset('cmpid',0);
             $supervisormodel = new CICompanySupervisorM();
             $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
             $supervisormodel->mGetEditorCMP($this->cStatusObj,$this->cDbObject);
             echo json_encode($this->cStatusObj['geteditrocmp']);
        }
        public function cgetcmpLocation(){
             $this->cCmpRoleid = cilib_isset('roleId',0);
             $this->cEditorid = cilib_isset('editor',0);
             $this->cCmpID = cilib_isset('cmpid',0);
             $supervisormodel = new CICompanySupervisorM();
             $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
             $supervisormodel->mGetEditorCmpLoc($this->cStatusObj,$this->cDbObject);
             echo json_encode($this->cStatusObj['getcmpLocation']);
            
            
        }
        public function cGetEditorLoc(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $this->cEditorid = cilib_isset('editor',0);
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mGetEditorLoc($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['geteditorloc']);
            log_message("info","Location===========>>".json_encode($this->cStatusObj));
       
        }
        public function cGetSupallActivity(){
            $this->cCmpRoleid = cilib_isset('roleId',0);
            if(!empty($this->cCmpRoleid) and $this->cCmpRoleid != null){
                $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
                $supervisormodel= new CICompanySupervisorM();
                $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
                $supervisormodel->mGetAllActivitiesDefault($this->cStatusObj,$this->cDbObject);
                echo json_encode($this->cStatusObj['getAllactivities']);
            }else{
                 echo json_encode(array(array()));
            }
        }
        public function cGetSupActivity(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $this->cEditorid = cilib_isset('editor',0);
            $this->cLoc = cilib_isset('loc',0);
            $this->cMonth = cilib_isset('selmonth',0);
            $this->cYear = cilib_isset('selyear',0);
            $this->cCmpID = cilib_isset('cmpid',0);
            
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            if($this->cEditorid == "all" && $this->cLoc == "all" && $this->cCmpID == "all"){                
                $supervisormodel->mGetSupDefaultActivity($this->cStatusObj,$this->cDbObject);
            }else{
                $supervisormodel->mGetSupActivities($this->cStatusObj,$this->cDbObject);
            }
            echo json_encode($this->cStatusObj['getAllactivities']);
            log_message("info","AllActivitiesssss===========>>".json_encode($this->cStatusObj));
        }
        public function cGetSupDefaultActivity(){
            
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $this->cMonth = date("m"); //$today = date("Y-m-d H:i:s");
            $this->cYear = date("Y");

            $supervisormodel = new CICompanySupervisorM();
            //$supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            $supervisormodel->mGetSupDefaultActivity($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['getAllactivities']);
            log_message("info","AllActivitiesssss===========>>".json_encode($this->cStatusObj));
               
        }
        public function cGetSupActivityDashboard(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $this->cEditorid = cilib_isset('editor',0);
            $this->cLoc = cilib_isset('loc',0);
            $this->cMonth = cilib_isset('selmonth',0);
            $this->cYear = cilib_isset('selyear',0);
            $this->cActid=cilib_isset('actid',0);
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            $supervisormodel->mGetSupActivitiesDashboard($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['getAllactivities']);
            log_message("info","AllActivitiesssss===========>>".json_encode($this->cStatusObj));
        }
        public function cGetSupEditorDetails(){
           $this->cEditorid = cilib_isset('editor',0);
           $this->cCmpRoleid = cilib_isset('roleId',0);
           $supervisormodel = new CICompanySupervisorM();
           $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
           $supervisormodel->mGetSupEditorDetails($this->cStatusObj,$this->cDbObject);
           echo json_encode($this->cStatusObj['getsupeditordetails']);
           log_message("info","AllActivitiesssss===========>>".json_encode($this->cStatusObj));
        
        }
        public function cGetEditorCompany(){
            $this->cCmpRoleid = cilib_isset('roleId',0);
            $this->cEditorid = cilib_isset('editor',0);
            if(!empty($this->cCmpRoleid) and $this->cCmpRoleid != null){
			$companiesmodel = new CICompanySupervisorM();
			$companiesmodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
			$companiesmodel->mGetEditorCompany($this->cStatusObj,$this->cDbObject);
			//$_SESSION['companyuser']['companieslist']=$this->cStatusObj['getuserCompanies'];			
			echo json_encode($this->cStatusObj['getuserCompanies']);
                        log_message("info","AllCompanies===========>>".json_encode($this->cStatusObj));
            }
        }
        public function cGetEditorCmpLocationList(){
            
            $this->cCmpRoleid = cilib_isset('roleId',0);
            $this->cEditorid = cilib_isset('editor',0);


            if(!empty($this->cCmpRoleid) and $this->cCmpRoleid != null){
            $companiesmodel = new CICompanySupervisorM();
            $companiesmodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            $companiesmodel->mGetEditorCmpLocationList($this->cStatusObj,$this->cDbObject);
            //$_SESSION['companyuser']['companieslist']=$this->cStatusObj['getuserCompanies'];          
            echo json_encode($this->cStatusObj['getuserCompaniesloc']);
                        log_message("info","AllCompanies===========>>".json_encode($this->cStatusObj));
            }

        }
        public function cGetEditorActivity(){
            $this->cCmpRoleid = cilib_isset('roleId',0);
            $this->cEditorid = cilib_isset('editor',0);
            $this->cfmonth = cilib_isset('fmonth',0);
            $this->cfdate = cilib_isset('fdate',0);
            $this->ctmonth = cilib_isset('tmonth',0);
            $this->ctdate = cilib_isset('tdate',0);
            $this->cCmpID = cilib_isset('cmpid',0);
            $this->cLocID = cilib_isset('locid',0);
            $this->cfyear = cilib_isset('fyear',0);
            $this->ctyear = cilib_isset('tyear',0);
            if(!empty($this->cCmpRoleid) and $this->cCmpRoleid != null){
                $supervisormodel = new CICompanySupervisorM();
		        $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
		        $supervisormodel->mGetEditorActivity($this->cStatusObj,$this->cDbObject);
		        echo json_encode($this->cStatusObj['getAllfinalactivities']);
                log_message("info","getactivity===========>>".json_encode($this->cStatusObj));
            }
        }
        public function cRejectActivity(){
           
            $this->ccmpactyid = cilib_isset('cmpactyid',0);
            $supervisormodel = new CICompanySupervisorM();
	    $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            $supervisormodel->mRejectActivity($this->cStatusObj,$this->cDbObject);
		
        }
        public function cAcceptActivity(){
            $this->ccmpactyid = cilib_isset('cmpactyid',0);
            $supervisormodel = new CICompanySupervisorM();
	    $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
            $supervisormodel->mAcceptActivity($this->cStatusObj,$this->cDbObject);
            
        }
        public function cFetchregdata(){
            $this->cCmpUserid = $_SESSION['companyuser']['user_id'];
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mFetchregdata($this->cStatusObj,$this->cDbObject);
            echo json_encode($this->cStatusObj['getrej']);
            log_message("info","editormail===========>>".json_encode($this->cStatusObj));
       
        }
        public function cRejapproval(){
            $this->cregid = cilib_isset('regid',0);
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mRejapproval($this->cStatusObj,$this->cDbObject);
            
        }
        public function cAcceptapproval(){
            $this->cregid = cilib_isset('regid',0);
            $supervisormodel = new CICompanySupervisorM();
            $supervisormodel->Initialize($this->cCmpUserid,NULL);
            $supervisormodel->mAcceptapproval($this->cStatusObj,$this->cDbObject);
            
        }
        public function cGetSupEditorRegCompleted(){
           $this->cEditorid = cilib_isset('editor',0);
           $this->cCmpRoleid = cilib_isset('roleId',0);
           $supervisormodel = new CICompanySupervisorM();
           $supervisormodel->Initialize($this->cCmpUserid,$this->cCmpRoleid);
           $supervisormodel->mGetSupEditorRegCompleted($this->cStatusObj,$this->cDbObject);
           echo json_encode($this->cStatusObj['getsupeditordetails']);
           log_message("info","AllActivitiesssss===========>>".json_encode($this->cStatusObj));
        }
}
        $controllerObj = new CICompanyUser();
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

