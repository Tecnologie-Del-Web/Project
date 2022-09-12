use tdw;
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

DELIMITER $$

CREATE FUNCTION add_order(order_code VARCHAR(20), total FLOAT, u_id INTEGER UNSIGNED, pay_id INTEGER UNSIGNED, a_id INTEGER UNSIGNED) RETURNS INTEGER UNSIGNED DETERMINISTIC
BEGIN
	INSERT INTO `order` (`order_code`, `updated_at`, `total`, `progress_status`, `user_id`, `payment_id`, `address_id`) VALUES (order_code, NOW(), total, 'Piazzato', u_id, pay_id, a_id);
    RETURN last_insert_id();
END$$

DELIMITER ;

DELIMITER $$

CREATE FUNCTION add_order_coupon(order_code VARCHAR(20), total FLOAT, u_id INTEGER UNSIGNED, pay_id INTEGER UNSIGNED, c_id INTEGER UNSIGNED, a_id INTEGER UNSIGNED) RETURNS INTEGER UNSIGNED DETERMINISTIC
BEGIN
	INSERT INTO `order` (`order_code`, `updated_at`, `total`, `progress_status`, `user_id`, `payment_id`, `coupon_id`, `address_id`) VALUES (order_code, NOW(), total, 'Piazzato', u_id, pay_id, c_id, a_id);
    RETURN last_insert_id();
END$$

DELIMITER ;