<?php
  enum METHOD{
  case CREATE;
  case UPDATE;
  case DELETE; 
  case DEACTIVATE; 
  case CANCEL;
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
  case Transaction;
  case Audit;
  }

  ENUM STATUS {
    case PENDING;
    case ACTIVE;
    case CANCELLED;
  }
?>