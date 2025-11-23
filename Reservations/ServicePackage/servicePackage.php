<?php
class ServicePackage
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function addOrUpdateRange(int $reservationId, $services)
  {
    $servicePackageList = [];
    $existingIdList = [];

    $sql = "SELECT * FROM servicePackage WHERE reservationId = $reservationId AND isActive";
    $result = mysqli_query($this->conn, query: $sql);

    while($servicePackages = mysqli_fetch_assoc($result)){
      $servicePackageList[$servicePackages['servicePackageId']] = $servicePackages;
    }
    foreach($services as $service){
      $serviceId = $service['serviceId'];
      $qty = $service['quantity'];
      if(empty($service['servicePackageId']))
      {
        $addServicePackage = "INSERT INTO servicePackage 
                                  (`reservationId`, `serviceId`, `quantity`) 
                                  VALUES('$reservationId', '$serviceId', '$qty')";
        mysqli_query($this->conn,$addServicePackage);
      }

      else{
        $id = $service['servicePackageId'];
        $qty = $service['quantity'];
        $existingIdList[] = $id; 
        $udpateServicePackage = "UPDATE servicePackage 
                                    SET 
                                        serviceId = $serviceId,
                                        quantity = $qty
                                    WHERE servicePackageId = $id";
        mysqli_query($this->conn, query: $udpateServicePackage);
      }
    }

    $deletedServicePackages = array_diff(array_keys($servicePackageList), $existingIdList);

    if (!empty($deletedServicePackages)) {
      foreach ($deletedServicePackages as $deletedServicePackageId) {
        $deleteServicePackage = "UPDATE servicePackage 
                                      SET isActive = 0 WHERE servicePackageId = $deletedServicePackageId";
        mysqli_query($this->conn, $deleteServicePackage);
      }
    }
  }
}
?>

