import axios from 'axios';

// Create axios instance with base configuration
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'https://quickcart-production.up.railway.app/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  register: (userData) => api.post('/auth/register', userData),
  login: (credentials) => api.post('/auth/login', credentials),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

// Products API
export const productsAPI = {
  getAll: (params = {}) => api.get('/v1/products', { params }),
  getById: (id) => api.get(`/v1/products/${id}`),
  getSimple: () => api.get('/products-simple'),
  create: (productData) => api.post('/v1/products', productData),
  update: (id, productData) => api.put(`/v1/products/${id}`, productData),
  delete: (id) => api.delete(`/v1/products/${id}`),
  updateStock: (id, stockData) => api.patch(`/v1/products/${id}/stock`, stockData),
};

// Cart API
export const cartAPI = {
  get: () => api.get('/cart'),
  addItem: (itemData) => api.post('/cart/items', itemData),
  updateItem: (itemId, itemData) => api.patch(`/cart/items/${itemId}`, itemData),
  removeItem: (itemId) => api.delete(`/cart/items/${itemId}`),
  clear: () => api.delete('/cart/clear'),
};

// Orders API
export const ordersAPI = {
  getAll: (params = {}) => api.get('/orders', { params }),
  getById: (id) => api.get(`/orders/${id}`),
  create: (orderData) => api.post('/orders', orderData),
  cancel: (id) => api.patch(`/orders/${id}/cancel`),
  updateStatus: (id, statusData) => api.patch(`/orders/${id}/status`, statusData),
};

// Health check
export const healthAPI = {
  check: () => api.get('/health'),
};

export default api;
