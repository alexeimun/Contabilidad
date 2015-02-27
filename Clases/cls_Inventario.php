<?php

    class cls_Inventario
    {
        //put your code here

        public $_prueba;
        public $_ExisteCodigo;
        private $_DB;

        public function __construct()
        {
            $this->_DB = DataBase::Connection();
        }
    }