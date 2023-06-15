import React from 'react';
import './App.scss';
import { BrowserRouter, Route, Routes } from "react-router-dom";
import Spaces from './components/spaces/Spaces';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route
          path="/*"
          element={
            <Spaces />
          }
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
