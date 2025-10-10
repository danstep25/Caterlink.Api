<?php
  enum METHOD{
  case CREATE;
  case UPDATE;
  case DELETE; 
  }

  enum MODULE {
  case User;
  case UserGroup;
  case Supplier;
  case Ingredients;
  case UnitOfMeasurement;
  case Dish;
  case Category;
  case FoodPackage;
  case Service;
  case Event;
  case Reservation;
  case Venue;
  }
?>