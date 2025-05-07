import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link, Navigate } from 'react-router-dom';
import Messenger from './pages/Messenger';
import styled from 'styled-components';

function App() {
  return (
    <Router>
      <AppContainer>
        <Nav>
          <NavLink to="/messenger">Messages</NavLink>
        </Nav>
        
        <MainContent>
          <Routes>
            <Route path="/" element={<Navigate to="/messenger" replace />} />
            <Route path="/messenger" element={<Messenger />} />
          </Routes>
        </MainContent>
      </AppContainer>
    </Router>
  );
}

const AppContainer = styled.div`
  height: 100vh;
  display: flex;
  flex-direction: column;
`;

const MainContent = styled.main`
  flex: 1;
  overflow: hidden;
`;

const Nav = styled.nav`
  padding: 1rem;
  background: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
`;

const NavLink = styled(Link)`
  text-decoration: none;
  color: #007bff;
  padding: 0.5rem 1rem;

  &:hover {
    color: #0056b3;
  }
`;

export default App;