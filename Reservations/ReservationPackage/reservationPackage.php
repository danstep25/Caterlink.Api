<?php
class ReservationPackage
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function addOrUpdateRange(int $reservationId, array $packages)
  {
    $servicePackageList = [];
    $existingIdList = [];

    $sql = "SELECT * FROM reservationpackage WHERE reservationId = $reservationId AND isActive";
    $result = mysqli_query($this->conn, query: $sql);

    while($reservations = mysqli_fetch_assoc($result)){
      $servicePackageList[$reservations['reservationPackageId']] = $reservations;
    }
    
    foreach($packages as $package){
      $packageId = $package['packageId'];
      if(empty($package['reservationPackageId']))
      {
        $addReservationPackage = "INSERT INTO reservationpackage 
                                  (`reservationId`, `packageId`) 
                                  VALUES('$reservationId', '$packageId')";
        mysqli_query($this->conn,$addReservationPackage);
      }

      else{
        $id = $package['reservationPackageId'];
        $existingIdList[] = $id; 
        $udpateReservationPackage = "UPDATE reservationpackage 
                                      SET packageId = $packageId WHERE reservationPackageId = $id";
        mysqli_query($this->conn, query: $udpateReservationPackage);
      }
    }

    $deletedReservationPackages = array_diff(array_keys($servicePackageList), $existingIdList);

    if (!empty($deletedReservationPackages)) {
      foreach ($deletedReservationPackages as $deleteReservationPackageId) {
        $deleteReservationPackage = "UPDATE reservationpackage 
                                      SET isActive = 0 WHERE reservationPackageId = $deleteReservationPackageId";
        mysqli_query($this->conn, $deleteReservationPackage);
      }
    }
  }
}
?>