import React, { createContext, useContext, useState, useEffect } from 'react';
import { cartAPI } from '../services/api';
import { useAuth } from './AuthContext';

const CartContext = createContext();

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};

export const CartProvider = ({ children }) => {
  const [cart, setCart] = useState(null);
  const [loading, setLoading] = useState(false);
  const [itemCount, setItemCount] = useState(0);
  const [total, setTotal] = useState(0);
  const { isAuthenticated } = useAuth();

  useEffect(() => {
    if (isAuthenticated) {
      fetchCart();
    } else {
      // For guest users, we could implement local storage cart
      setCart(null);
      setItemCount(0);
      setTotal(0);
    }
  }, [isAuthenticated]);

  const fetchCart = async () => {
    try {
      setLoading(true);
      const response = await cartAPI.get();
      const cartData = response.data.data;
      setCart(cartData.cart);
      setItemCount(cartData.total_quantity || 0);
      setTotal(cartData.total || 0);
    } catch (error) {
      console.error('Error fetching cart:', error);
      setCart(null);
      setItemCount(0);
      setTotal(0);
    } finally {
      setLoading(false);
    }
  };

  const addToCart = async (productId, quantity = 1) => {
    try {
      setLoading(true);
      const response = await cartAPI.addItem({ product_id: productId, quantity });
      const cartData = response.data.data;
      setCart(cartData.cart);
      setItemCount(cartData.total_quantity || 0);
      setTotal(cartData.total || 0);
      return { success: true };
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to add item to cart';
      return { success: false, error: message };
    } finally {
      setLoading(false);
    }
  };

  const updateCartItem = async (itemId, quantity) => {
    try {
      setLoading(true);
      const response = await cartAPI.updateItem(itemId, { quantity });
      const cartData = response.data.data;
      setCart(cartData.cart);
      setItemCount(cartData.total_quantity || 0);
      setTotal(cartData.total || 0);
      return { success: true };
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to update cart item';
      return { success: false, error: message };
    } finally {
      setLoading(false);
    }
  };

  const removeFromCart = async (itemId) => {
    try {
      setLoading(true);
      const response = await cartAPI.removeItem(itemId);
      const cartData = response.data.data;
      setCart(cartData.cart);
      setItemCount(cartData.total_quantity || 0);
      setTotal(cartData.total || 0);
      return { success: true };
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to remove item from cart';
      return { success: false, error: message };
    } finally {
      setLoading(false);
    }
  };

  const clearCart = async () => {
    try {
      setLoading(true);
      await cartAPI.clear();
      setCart(null);
      setItemCount(0);
      setTotal(0);
      return { success: true };
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to clear cart';
      return { success: false, error: message };
    } finally {
      setLoading(false);
    }
  };

  const value = {
    cart,
    loading,
    itemCount,
    total,
    fetchCart,
    addToCart,
    updateCartItem,
    removeFromCart,
    clearCart,
  };

  return (
    <CartContext.Provider value={value}>
      {children}
    </CartContext.Provider>
  );
};
