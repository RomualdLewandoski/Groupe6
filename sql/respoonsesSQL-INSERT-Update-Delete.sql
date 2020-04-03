--EXERCISE 6
DELETE products.*
FROM products
INNER JOIN brands ON brands.brand_id = products.brand_id
WHERE brands.brand_name = "Trek"

--EXERCISE 7
DELETE products.*
FROM products
INNER JOIN categories ON categories.idCat = products.category_id
WHERE categories.nameCat = "Mountain Bikes"

--EXERCISE 8
UPDATE products SET products.price = "1400" WHERE products.id = 9

--Exercise 9
INSERT INTO categories (categories.idCat, categories.nameCat) VALUES(null, "Roller skates")

--Exercise 10
INSERT INTO products (products.id, products.name, products.brand_id, products.category_id, products.model_year, products.price) 
VALUES (null,
	"Roller skates cool", 
	(SELECT brands.brand_id FROM brands WHERE brands.brand_name = 'Haro'),
	(SELECT categories.idCat FROM categories WHERE categories.nameCat = 'Roller skates'),
	"2020", 
	"258"
)