<?php
    namespace App\Forms;

    use App\Forms\Validator\Validator;
    use App\Db\DB;

    abstract class MainForm
    {
        protected $paramRules = [];
        protected $validateParams = [];
        protected $valid = false;
        protected $validator;
        protected $data;
        protected $db;
        public abstract function isValid();

        public function __construct($requestData)
        {
            $this->data = $this->isEmpty($requestData);
            $this->validator = Validator::getInstance();
            $this->validateParams = $this->getNonEmptyParams();
            $this->db = DB::getInstance();
        }

        private function isEmpty($data) 
        {
            if (empty($data)){
                return [
                    'surname' => '',
                    'name' => '',
                    'phoneNumber' => '',
                    'message' => '',
                    'list' => ''
                ];
            } 
            return $data;
        }

        private function getNonEmptyParams()
        {
            $newParamData = [];
            $ruleKeys = array_keys($this->paramRules);
            foreach ($ruleKeys as $ruleKey) {
                $newParamData[$ruleKey] = $this->data[$ruleKey];
            }
            return $newParamData;
        }

        public function validation($validateParams)
        {
            foreach ($validateParams as $type => $param) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
            return !empty($this->validator->getErrors());
        }

        public function getFormData()
        {
            $params = [];
            if (!$this->valid) {
                foreach ($this->data as $dataKey => $dataElement) {
                    $params[$dataKey] = htmlspecialchars($dataElement, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, "UTF-8");
                }
            } else {
                foreach ($this->data as $dataKey => $dataElement) {
                    $params[$dataKey] = "";
                }
            }
            $params['list'] = $this->db->getSelectData();
            return $params;
        }
    }
?>