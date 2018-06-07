<?php
    //database wrapper class
    class ProjectDB{
        private static $_instance = null;
        //only accessible within that class
        private $_pdo,
                $_query,
                $_error = false,
                $_results,
                $_count = 0;
        /*
         pdo - php data object
         query - last executed query
         error - any validation issues
         results - results set of records queried
         count - how many records are returned as results
        */

        private function __construct(){
            try{
                $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' .Config::get('mysql/db'),
                    Config::get('mysql/username'), Config::get('mysql/password'));
               // echo 'Connected';
            } catch(PDOException $e){
                die($e->getMessage());
            }
        }

        public static function getInstance(){
            if(!isset(self::$_instance)){
                self::$_instance = new ProjectDB();
                //if db is not instantiated it is instantiated here
                //avoids instantiating more than once if called multiple times on a page
            }
            return self::$_instance;//return db instance
        }

        public function query($sql, $params = array()){
            $this->_error = false; //clear errors, prevent returning an error for previous query
            if($this->_query = $this->_pdo->prepare($sql)){
                //check if sql is prepared and assign the query to the pdo
                $i = 1;
                if(count($params)){
                    foreach($params as $param){
                        $this->_query->bindValue($i, $param);
                        $i++;
                    }
                }
                if($this->_query->execute()){
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count=$this->_query->rowCount();
                }else{
                    $this->_error=true;
                }
            }
            return $this;
        }
        //set to private for security reasons if this method is only going to be used in this class
        //currently set to public in case it needs to be used in other classes

        public function queryAll($sql, $params = array()){
            $this->_error = false; //clear errors, prevent returning an error for previous query
            if($this->_query = $this->_pdo->prepare($sql)){
                //check if sql is prepared and assign the query to the pdo
                $i = 1;
                if(count($params)){
                    foreach($params as $param){
                        $this->_query->bindValue($i, $param);
                        $i++;
                    }
                }
                if($this->_query->execute()){
                    $rows=$this->_results = $this->_query->fetchAll(PDO::FETCH_ASSOC);
                        $this->_count = $this->_query->rowCount();

                }else{
                    $this->_error=true;
                }
            }
            return $rows;
        }


        public function action($action, $table, $where = array()){
            //check for three parameters - field, operator, value
            if(count($where) === 3){
                  $operators = array('=', '>', '<', '>=', '<=');
                  $field = $where[0];
                  $operator = $where[1];
                  $value = $where[2];

                  //check if the operator passed in the action matches any of the available ones defined in the $operators array
                  if(in_array($operator, $operators)){
                      $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                      //? represents the value in a passed in array of field values
                        if(!$this->query($sql, array($value))->error()) {
                            return $this;//return the current object
                        }
                  }
            }
            return false;//if there is an error with the query or its syntax is invalid return false
        }

        //get record from table using a specified query
        public function get($table, $where){
            return $this->action('SELECT *', $table, $where);
        }

        //delete records from table with a specified condition
        public function delete($table, $where){
            return $this->action('DELETE', $table, $where);
        }

        //insert records in table
        public function insert($table, $fields = array()){
            if(count($fields)){
                $keys = array_keys($fields);
                $values = null;
                $i = 1;

                foreach($fields as $field){
                    $values .= "?";
                    if($i < count($fields)){
                        $values .= ', ';
                    }
                    $i++;
                }

                $sql = "INSERT INTO $table (`" .implode('`, `', $keys) . "`) VALUES ({$values})";
                if(!$this->query($sql, $fields) -> error()){
                    return true;
                }
            }
            return false;
        }

        //update records in table
        public function update($table, $primary_key, $id, $fields){
            $set = '';
            $i = 1;

            foreach($fields as $name => $value){
                $set .= "{$name} = ?";//use ? to avoid sql injection through data passed into input fields
                if($i < count($fields)){
                    $set .=', ';
                }
                $i++;
            }

            $sql = "UPDATE {$table} SET {$set} WHERE $primary_key = {$id}";
            if(!$this->query($sql, $fields)->error()){
                return true;
            }
            return false;
        }

        //get query results
        public function results(){
            return $this->_results;
        }

        public function getFirst(){
            return $this->results()[0];//calling the results() method
        }

        public function error(){
            return $this->_error;
        }

        public function count(){
            return $this->_count;
        }
    }

    //Code reusability
    //PHP PDO Why
    //MySQL why - supports PDO
    //singleton pattern getInstance() - only instantiate db once and improve efficiency
    //WHY if(!isset(self::$_instance)) for public static function tutorial 7