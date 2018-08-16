<?php


class KioskAssignment {
  private $data;
  public function __construct($json) {
    $data = json_decode($json);
  }
  public function getAssignmentType() {
    return dto\KioskAssignmentTypes::EVENTATTENDANCEKIOSK;
  }
  
  public function toJSON(){
    
  }
}
