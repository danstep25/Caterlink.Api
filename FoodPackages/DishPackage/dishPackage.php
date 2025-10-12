<?php
class DishPackage
{
  private mysqli $conn;
  function __construct($conn){
    $this->conn = $conn;
  }

  public function addOrUpdateRange(int $foodPackageId, array $packages)
  {
    $dishPackageList = [];
    $existingIdList = [];

    $sql = "SELECT * FROM dishpackage WHERE foodPackageId = $foodPackageId AND isActive";
    $result = mysqli_query($this->conn, query: $sql);

    while($foodPackage = mysqli_fetch_assoc($result)){
      $dishPackageList[$foodPackage['dishPackageId']] = $foodPackage;
    }
    
    foreach($packages as $package){
      $dishId = $package['dishId'];
      $servingSize = $package['servingSize'];
      if(empty($package['dishPackageId']))
      {
        $addDishPackage = "INSERT INTO dishpackage 
                                  (`foodPackageId`, `dishId`, `servingSize`) 
                                  VALUES('$foodPackageId', '$dishId', '$servingSize')";
        mysqli_query($this->conn,$addDishPackage);
      }

      else{
        $id = $package['dishPackageId'];
        $existingIdList[] = $id; 
        $udpateDishPackage = "UPDATE dishpackage 
                                      SET dishId = $dishId, `servingSize`= $servingSize WHERE dishPackageId = $id";
        mysqli_query($this->conn, query: $udpateDishPackage);
      }
    }

    $deletedDishPackages = array_diff(array_keys($dishPackageList), $existingIdList);

    if (!empty($deletedDishPackages)) {
      foreach ($deletedDishPackages as $deleteDishPackageId) {
        $deleteDishPackage = "UPDATE dishpackage 
                                      SET isActive = 0 WHERE dishPackageId = $deleteDishPackageId";
        mysqli_query($this->conn, $deleteDishPackage);
      }
    }
  }
}
?>