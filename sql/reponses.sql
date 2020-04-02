--Exercise 1
SELECT p.product_name AS pName, 
		p.model_year AS pYear,
		p.list_price AS pPrice,
		cat.category_id AS catId, 
		cat.category_name AS catName, 
		b.brand_name AS brandName 
FROM Category AS cat , Brand as b, Product AS p,  
WHERE b.brand_id = 'HARO'

--Exercise 2
SELECT p.product_name AS pName, 
	   p.model_year AS pYear,
	   p.list_price AS pPriceHt, 
	   p.list_price*20/100 AS p.PriceTTC, 
	   cat.category_id AS catId, 
	   cat.category_name AS catName, 
	   b.brand_name AS bName 
FROM Product AS p, Category AS cat, Brand AS b  
WHERE b.brand_name 'Haro';

--Exercise 3
SELECT * FROM Product AS p WHERE p.list_price > 500 AND p.list_price < 1500;

--Exercise 4
SELECT p.product_name, p.model_year, p.list_price FROM Product AS p, Brand AS b WHERE b.brand_name LIKE 'H%';

--Exercise 5
SELECT p.product_name, p.model_year, p.list_price FROM Product AS p WHERE p.product_name LIKE '%Ice%';

--Exercise 6
SELECT p.* FROM Product AS p, Brand as b WHERE b.brand_name = 'Trek';

--Exercise 7
BEGIN 
DECLARE @catId INT ;

	SELECT cat.category_id FROM Category AS cat WHERE cat.category_name = 'Mountain Bikes' INTO @catId ;
	BEGIN
		DELETE * FROM Product AS p WHERE p.cateogry_id = @catId;
	END;
END;

--Exercise 8
UPDATE Product AS p SET p.list_price = 1499 WHERE p.product_id = 9; 

--EXERCISE 9
INSERT INTO Category (category_id, category_name) VALUES( null, 'Roller skates'):

--Exercise 10
BEGIN
	DECLARE @brandId INT ;
	DECLARE @catId INT ;
	SELECT brand_id FROM Brand WHERE brand_name = 'Haro' INTO @brandId ;
	SELECT category_id FROM Category WHERE category_name = ‘Roller skates’ INTO @catId ;
	BEGIN
		INSERT INTO Product (product_id, product_name, brand_id, category_id, model_year, list_price) VALUES(null, 'roller skates cool', @brandId, @catId, 2020, 258.00) ;
	END ;
END ;

