<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Output_User_Event implements Event_Interface {
    public function run() {
        echo View::render();
        return true;
    }
}
?>
