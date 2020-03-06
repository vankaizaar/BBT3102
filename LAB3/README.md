# Task
1. Modify the User class constructor appropriately to accommodate the new data.   
2. Add the extra member variables in the class User plus their getters and setters. 
3. Proceed and modify the save() function appropriately to add the extra data into the database.

> Rewrote function so that timestamp and user timezone e.g "Africa/Nairobi" is sent in case js is available. So that only PHP computes time difference. Reason being js computes the offset differently . e.g.
  For Nairobi it will state -180 mins(from the users perspective) while if js is off PHP will compute 180(from GMT perspective). Hence to standardize this we compute from GMT as opposed from client side.

