<?php 

/* 
 * Previous Query 
 */ 
"SELECT SQL_CALC_FOUND_ROWS   
  listings.id, listings.slug, listings.token, listings.name, listings.address_1, listings.capacity ,     listings.city, listings.price, listings.status, listings.venue_type_id, user_favourites.favourite_id, 
  (SELECT ROUND(SUM((
     (listings_reviews.comfort_rating + 
       listings_reviews.location_rating + 
       listings_reviews.facility_rating + listings_reviews.value_rating) \/ 4))) 
  FROM listings_reviews 
   WHERE listings_reviews.listings_id=listings.id AND listings_reviews.approved=1) AS averageReview,
  (SELECT COUNT(*) 
    FROM listings_reviews 
    WHERE listings_reviews.listings_id=listings.id AND listings_reviews.approved=1) AS       totalReviews, 
  (SELECT COUNT(listings_gallery.listings_id) 
     FROM listings_gallery WHERE listings_gallery.listings_id=listings.id) AS image_count,
  (SELECT Count(*) 
     FROM listings_offers 
     WHERE listings_offers.listings_id=listings.id 
        AND listings_offers.status=1
  ) AS offer 
  FROM listings 
LEFT JOIN user_favourites 
 ON listings.token=user_favourites.favourite_listing 
  AND user_favourites.favourite_user=0 
HAVING listings.status = 1 
ORDER BY image_count DESC LIMIT 0,8"

/* 
 * Optimised Query  
 */ 
"SELECT l.id,  l.slug,  l.token, l.name, l.address_1, l.capacity, l.city, l.price, l.status, l.venue_type_id, 
   ROUND(
        (SUM(lr.comfort_rating + lr.location_rating + lr.facility_rating + lr.value_rating) / 4)
          / COUNT(lr.id)
      ) AS averageReview, 
COUNT(lr.id) AS totalReviews, 
lg.image_count, lg.images, lo.offer_count, lo.offer_names, uf.favourite_id
   FROM listings l
      LEFT JOIN listings_reviews AS lr ON lr.listings_id = l.id
      LEFT JOIN
        (
          SELECT listings_id, count(*) AS image_count,
            GROUP_CONCAT(thumbnail_path) AS images
          FROM listings_gallery
          GROUP BY listings_id
        ) lg
      ON lg.listings_id = l.id
      LEFT JOIN
        (
          SELECT listings_id, count(*) AS offer_count,
            GROUP_CONCAT(offer_name) AS offer_names
          FROM listings_offers
          GROUP BY listings_id
        ) lo
      ON lo.listings_id = l.id
      LEFT JOIN user_favourites AS uf
        ON uf.favourite_listing = l.token
        AND l.status = 1

      GROUP BY l.id, l.slug, l.token, l.name, l.address_1,
      l.capacity, l.city, l.price, l.status, l.venue_type_id
      DESC LIMIT 0,8"
