import { BrowserRouter, Routes, Route } from "react-router-dom"
import Home from "./pages/home"
import './styling/css/App.css'

function App() {

  return (
    <div className="App">
      {/* <Nav /> */}
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />
        </Routes>
      </BrowserRouter>
      {/* <Footer /> */}
    </div>
  )
}

export default App
