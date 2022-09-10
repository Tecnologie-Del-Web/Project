DELIMITER $$

CREATE PROCEDURE adjust_price(u_id INTEGER UNSIGNED, p_id INTEGER UNSIGNED, increment TINYINT)
BEGIN
	DECLARE old_quantity SMALLINT;
    DECLARE product_price FLOAT;
	SELECT quantity FROM user_product_cart WHERE user_id = u_id AND product_id = p_id INTO old_quantity;
    SELECT price FROM product WHERE product_id = p_id INTO product_price;
    IF (increment < 0) THEN
		IF (old_quantity = 1) THEN
			DELETE FROM user_product_cart WHERE user_id = u_id AND product_id = p_id;
		ELSE
			UPDATE user_product_cart SET quantity = quantity - 1, subtotal = subtotal - product_price WHERE user_id = u_id AND product_id = p_id;
		END IF;
	ELSE
		UPDATE user_product_cart SET quantity = quantity + 1, subtotal = subtotal + product_price WHERE user_id = u_id AND product_id = p_id;
    END IF;
END$$

DELIMITER ;