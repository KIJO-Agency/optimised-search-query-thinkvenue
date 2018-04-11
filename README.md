# Optimised search query for Think Venue
Optimised search query by reducing search space for better performance. 



A client came to use with a problem. There search for venues was taking approximately 45 seconds. It originally worked fine however they experienced performance issues after inserting over 6000 rows of data into the database. The First thing I did was remove the sub queries which where far to expensive. Then I reduced the search space of the joins. E.I. If I have multiple tables. I do not need to search table B for every row in table A to get all the data I need. I only need to search table B in one row for table A. 

With these adjustments KIJO was able to reduce the search speed from 45 seconds to just  fraction of a second. 
