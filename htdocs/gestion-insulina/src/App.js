import React, { useState } from "react";
import UserTable from "./components/UserTable";
import UserForm from "./components/UserForm";

function App() {
  const [refresh, setRefresh] = useState(false);

  return (
    <div className="App">
      <h1>Gestión de Usuarios</h1>
      <UserForm onUserAdded={() => setRefresh(!refresh)} />
      <UserTable key={refresh} />
    </div>
  );
}

export default App;
