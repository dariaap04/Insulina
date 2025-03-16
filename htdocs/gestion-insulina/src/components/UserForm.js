import React, { useState } from "react";
import axios from "axios";

const UserForm = ({ onUserAdded }) => {
  const [user, setUser] = useState({
    nombre: "",
    apellidos: "",
    usuario: "",
    contra: "",
    fecha_nacimiento: "",
  });

  // Expresiones regulares para validación
  const usernameRegex = /^[a-z][a-z0-9]{5,}$/;
  const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

  const handleChange = (e) => {
    setUser({ ...user, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // Validaciones
    if (!usernameRegex.test(user.usuario)) {
      alert("El nombre de usuario debe tener al menos 6 caracteres, solo minúsculas y números.");
      return;
    }
    if (!passwordRegex.test(user.contra)) {
      alert("La contraseña debe tener mínimo 8 caracteres, una mayúscula y un número.");
      return;
    }
    const birthDate = new Date(user.fecha_nacimiento);
    const today = new Date();
    const age = today.getFullYear() - birthDate.getFullYear();
    if (age < 18) {
      alert("El usuario debe ser mayor de 18 años.");
      return;
    }

    axios
      .post("http://localhost/gestion-insulina-backend/users.php", user)
      .then(() => {
        alert("Usuario añadido correctamente");
        onUserAdded(); // Recargar la lista de usuarios
      })
      .catch((error) => {
        console.error("Error al agregar usuario:", error);
      });
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Agregar Usuario</h2>
      <input type="text" name="nombre" placeholder="Nombre" onChange={handleChange} required />
      <input type="text" name="apellidos" placeholder="Apellidos" onChange={handleChange} required />
      <input type="text" name="usuario" placeholder="Usuario" onChange={handleChange} required />
      <input type="password" name="contra" placeholder="Contraseña" onChange={handleChange} required />
      <input type="date" name="fecha_nacimiento" onChange={handleChange} required />
      <button type="submit">Agregar</button>
    </form>
  );
};

export default UserForm;
