import React, { useState, useEffect } from 'react';

function App() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState('home');
  const [cart, setCart] = useState([]);
  const [showMessage, setShowMessage] = useState('');

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const response = await fetch('https://quickcart-production.up.railway.app/api/products-simple');
      const data = await response.json();
      setProducts(data.data || []);
    } catch (error) {
      console.error('Error fetching products:', error);
      setShowMessage('Error loading products. Please try again later.');
    } finally {
      setLoading(false);
    }
  };

  const addToCart = (product) => {
    const existingItem = cart.find(item => item.id === product.id);
    if (existingItem) {
      setCart(cart.map(item =>
        item.id === product.id
          ? { ...item, quantity: item.quantity + 1 }
          : item
      ));
    } else {
      setCart([...cart, { ...product, quantity: 1 }]);
    }
    setShowMessage(`${product.name} added to cart!`);
    setTimeout(() => setShowMessage(''), 3000);
  };

  const removeFromCart = (productId) => {
    setCart(cart.filter(item => item.id !== productId));
    setShowMessage('Item removed from cart');
    setTimeout(() => setShowMessage(''), 3000);
  };

  const getTotalItems = () => {
    return cart.reduce((total, item) => total + item.quantity, 0);
  };

  const getTotalPrice = () => {
    return cart.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0).toFixed(2);
  };

  return (
    <div style={{ minHeight: '100vh' }}>
      {/* Message Banner */}
      {showMessage && (
        <div style={{
          background: '#10b981',
          color: 'white',
          padding: '0.75rem',
          textAlign: 'center',
          position: 'fixed',
          top: 0,
          left: 0,
          right: 0,
          zIndex: 1000
        }}>
          {showMessage}
        </div>
      )}

      {/* Navigation */}
      <nav className="navbar" style={{ marginTop: showMessage ? '3rem' : '0' }}>
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1
            style={{ fontSize: '1.5rem', fontWeight: 'bold', color: '#2563eb', cursor: 'pointer' }}
            onClick={() => setCurrentPage('home')}
          >
            QuickCart
          </h1>
          <div style={{ display: 'flex', gap: '1rem', alignItems: 'center' }}>
            <button
              onClick={() => setCurrentPage('home')}
              style={{
                background: 'none',
                border: 'none',
                color: currentPage === 'home' ? '#2563eb' : '#374151',
                cursor: 'pointer',
                fontWeight: currentPage === 'home' ? 'bold' : 'normal'
              }}
            >
              Home
            </button>
            <button
              onClick={() => setCurrentPage('products')}
              style={{
                background: 'none',
                border: 'none',
                color: currentPage === 'products' ? '#2563eb' : '#374151',
                cursor: 'pointer',
                fontWeight: currentPage === 'products' ? 'bold' : 'normal'
              }}
            >
              Products
            </button>
            <button
              onClick={() => setCurrentPage('cart')}
              style={{
                background: 'none',
                border: 'none',
                color: currentPage === 'cart' ? '#2563eb' : '#374151',
                cursor: 'pointer',
                fontWeight: currentPage === 'cart' ? 'bold' : 'normal',
                position: 'relative'
              }}
            >
              Cart
              {getTotalItems() > 0 && (
                <span style={{
                  background: '#ef4444',
                  color: 'white',
                  borderRadius: '50%',
                  padding: '0.25rem 0.5rem',
                  fontSize: '0.75rem',
                  position: 'absolute',
                  top: '-0.5rem',
                  right: '-0.5rem',
                  minWidth: '1.25rem',
                  textAlign: 'center'
                }}>
                  {getTotalItems()}
                </span>
              )}
            </button>
            <button
              onClick={() => setCurrentPage('login')}
              style={{
                background: '#2563eb',
                color: 'white',
                border: 'none',
                padding: '0.5rem 1rem',
                borderRadius: '0.375rem',
                cursor: 'pointer'
              }}
            >
              Login
            </button>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main>
        {currentPage === 'home' && (
          <>
            {/* Hero Section */}
            <section className="hero">
              <div className="container">
                <h1 style={{ fontSize: '3rem', fontWeight: 'bold', marginBottom: '1rem' }}>
                  Welcome to QuickCart
                </h1>
                <p style={{ fontSize: '1.25rem', marginBottom: '2rem', opacity: 0.9 }}>
                  Your one-stop shop for everything you need
                </p>
                <button
                  className="btn-primary"
                  style={{ fontSize: '1.1rem', padding: '0.75rem 2rem' }}
                  onClick={() => setCurrentPage('products')}
                >
                  Shop Now
                </button>
              </div>
            </section>

            {/* Featured Products */}
            <section style={{ padding: '4rem 0' }}>
              <div className="container">
                <h2 style={{ fontSize: '2rem', fontWeight: 'bold', textAlign: 'center', marginBottom: '3rem' }}>
                  Featured Products
                </h2>

                {loading ? (
                  <div style={{ textAlign: 'center', padding: '2rem' }}>
                    <p>Loading products...</p>
                  </div>
                ) : (
                  <div className="product-grid">
                    {products.slice(0, 6).map((product) => (
                      <div key={product.id} className="product-card">
                        <div
                          className="product-image"
                          style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontSize: '0.875rem',
                            color: '#6b7280'
                          }}
                        >
                          {product.name}
                        </div>
                        <div className="product-info">
                          <h3 style={{ fontSize: '1.1rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                            {product.name}
                          </h3>
                          <p style={{ fontSize: '1.5rem', fontWeight: 'bold', color: '#2563eb', marginBottom: '1rem' }}>
                            ${product.price}
                          </p>
                          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                            <span style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                              Stock: {product.stock_quantity}
                            </span>
                            <button
                              className="btn-primary"
                              style={{ fontSize: '0.875rem', padding: '0.5rem 1rem' }}
                              onClick={() => addToCart(product)}
                              disabled={product.stock_quantity === 0}
                            >
                              Add to Cart
                            </button>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                )}

                <div style={{ textAlign: 'center', marginTop: '2rem' }}>
                  <button
                    className="btn-primary"
                    onClick={() => setCurrentPage('products')}
                  >
                    View All Products
                  </button>
                </div>
              </div>
            </section>
          </>
        )}

        {currentPage === 'products' && (
          <section style={{ padding: '4rem 0' }}>
            <div className="container">
              <h2 style={{ fontSize: '2rem', fontWeight: 'bold', textAlign: 'center', marginBottom: '3rem' }}>
                All Products
              </h2>

              {loading ? (
                <div style={{ textAlign: 'center', padding: '2rem' }}>
                  <p>Loading products...</p>
                </div>
              ) : (
                <div className="product-grid">
                  {products.map((product) => (
                    <div key={product.id} className="product-card">
                      <div
                        className="product-image"
                        style={{
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          fontSize: '0.875rem',
                          color: '#6b7280'
                        }}
                      >
                        {product.name}
                      </div>
                      <div className="product-info">
                        <h3 style={{ fontSize: '1.1rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                          {product.name}
                        </h3>
                        <p style={{ fontSize: '1.5rem', fontWeight: 'bold', color: '#2563eb', marginBottom: '1rem' }}>
                          ${product.price}
                        </p>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                          <span style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                            Stock: {product.stock_quantity}
                          </span>
                          <button
                            className="btn-primary"
                            style={{ fontSize: '0.875rem', padding: '0.5rem 1rem' }}
                            onClick={() => addToCart(product)}
                            disabled={product.stock_quantity === 0}
                          >
                            Add to Cart
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </section>
        )}

        {currentPage === 'cart' && (
          <section style={{ padding: '4rem 0' }}>
            <div className="container">
              <h2 style={{ fontSize: '2rem', fontWeight: 'bold', textAlign: 'center', marginBottom: '3rem' }}>
                Shopping Cart
              </h2>

              {cart.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '4rem 0' }}>
                  <p style={{ fontSize: '1.25rem', color: '#6b7280', marginBottom: '2rem' }}>
                    Your cart is empty
                  </p>
                  <button
                    className="btn-primary"
                    onClick={() => setCurrentPage('products')}
                  >
                    Start Shopping
                  </button>
                </div>
              ) : (
                <div style={{ maxWidth: '800px', margin: '0 auto' }}>
                  {cart.map((item) => (
                    <div key={item.id} className="card" style={{ marginBottom: '1rem', padding: '1.5rem' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <div>
                          <h3 style={{ fontSize: '1.25rem', fontWeight: '600', marginBottom: '0.5rem' }}>
                            {item.name}
                          </h3>
                          <p style={{ color: '#6b7280' }}>
                            ${item.price} × {item.quantity} = ${(parseFloat(item.price) * item.quantity).toFixed(2)}
                          </p>
                        </div>
                        <button
                          onClick={() => removeFromCart(item.id)}
                          style={{
                            background: '#ef4444',
                            color: 'white',
                            border: 'none',
                            padding: '0.5rem 1rem',
                            borderRadius: '0.375rem',
                            cursor: 'pointer'
                          }}
                        >
                          Remove
                        </button>
                      </div>
                    </div>
                  ))}

                  <div className="card" style={{ padding: '1.5rem', marginTop: '2rem' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                      <h3 style={{ fontSize: '1.5rem', fontWeight: 'bold' }}>
                        Total: ${getTotalPrice()}
                      </h3>
                      <button className="btn-primary" style={{ padding: '0.75rem 2rem' }}>
                        Checkout
                      </button>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </section>
        )}

        {currentPage === 'login' && (
          <section style={{ padding: '4rem 0' }}>
            <div className="container">
              <div style={{ maxWidth: '400px', margin: '0 auto' }}>
                <h2 style={{ fontSize: '2rem', fontWeight: 'bold', textAlign: 'center', marginBottom: '3rem' }}>
                  Login
                </h2>
                <div className="card" style={{ padding: '2rem' }}>
                  <p style={{ textAlign: 'center', color: '#6b7280', marginBottom: '2rem' }}>
                    Login functionality will be implemented with full authentication system.
                  </p>
                  <div style={{ marginBottom: '1rem' }}>
                    <label style={{ display: 'block', marginBottom: '0.5rem', fontWeight: '500' }}>Email</label>
                    <input
                      type="email"
                      placeholder="Enter your email"
                      style={{
                        width: '100%',
                        padding: '0.75rem',
                        border: '1px solid #d1d5db',
                        borderRadius: '0.375rem',
                        fontSize: '1rem'
                      }}
                    />
                  </div>
                  <div style={{ marginBottom: '2rem' }}>
                    <label style={{ display: 'block', marginBottom: '0.5rem', fontWeight: '500' }}>Password</label>
                    <input
                      type="password"
                      placeholder="Enter your password"
                      style={{
                        width: '100%',
                        padding: '0.75rem',
                        border: '1px solid #d1d5db',
                        borderRadius: '0.375rem',
                        fontSize: '1rem'
                      }}
                    />
                  </div>
                  <button className="btn-primary" style={{ width: '100%', padding: '0.75rem' }}>
                    Sign In
                  </button>
                </div>
              </div>
            </div>
          </section>
        )}
      </main>

      {/* Footer */}
      <footer className="footer">
        <div className="container">
          <div style={{ textAlign: 'center' }}>
            <h3 style={{ fontSize: '1.5rem', fontWeight: 'bold', marginBottom: '1rem' }}>QuickCart</h3>
            <p style={{ opacity: 0.8, marginBottom: '1rem' }}>
              Your one-stop shop for all your needs. Fast delivery, great prices, and excellent customer service.
            </p>
            <p style={{ fontSize: '0.875rem', opacity: 0.6 }}>
              © 2025 QuickCart. All rights reserved. Built with React and Laravel.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
}

export default App;
