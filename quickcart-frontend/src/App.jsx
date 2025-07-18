import React, { useState, useEffect } from 'react';

function App() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

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
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ minHeight: '100vh' }}>
      {/* Navigation */}
      <nav className="navbar">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1 style={{ fontSize: '1.5rem', fontWeight: 'bold', color: '#2563eb' }}>QuickCart</h1>
          <div style={{ display: 'flex', gap: '1rem' }}>
            <a href="#" style={{ textDecoration: 'none', color: '#374151' }}>Home</a>
            <a href="#" style={{ textDecoration: 'none', color: '#374151' }}>Products</a>
            <a href="#" style={{ textDecoration: 'none', color: '#374151' }}>Cart</a>
            <a href="#" style={{ textDecoration: 'none', color: '#374151' }}>Login</a>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="hero">
        <div className="container">
          <h1 style={{ fontSize: '3rem', fontWeight: 'bold', marginBottom: '1rem' }}>
            Welcome to QuickCart
          </h1>
          <p style={{ fontSize: '1.25rem', marginBottom: '2rem', opacity: 0.9 }}>
            Your one-stop shop for everything you need
          </p>
          <button className="btn-primary" style={{ fontSize: '1.1rem', padding: '0.75rem 2rem' }}>
            Shop Now
          </button>
        </div>
      </section>

      {/* Products Section */}
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
