import React, { useEffect, useState } from "react";
import axios from "axios";

const UserTable = () => {
  const [users, setUsers] = useState([]);

  // Obtener usuarios
  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = () => {
    axios
      .get("http://localhost/gestion-insulina/backend/users.php")
      .then((response) => {
        setUsers(response.data);
      })
      .catch((error) => {
        console.error("Error al obtener usuarios:", error);
      });
  };

  // Eliminar usuario
  const deleteUser = (id) => {
    if (window.confirm("¿Seguro que quieres eliminar este usuario?")) {
      axios
        .delete(`http://localhost/gestion-insulina/backend/users.php?id=${id}`)
        .then(() => {
          fetchUsers(); // Recargar usuarios tras eliminar
        })
        .catch((error) => {
          console.error("Error al eliminar usuario:", error);
        });
    }
  };

  // Editar usuario
  const editUser = (id) => {
    // Abre una ventana modal para editar el usuario con los datos actuales
  };

  return (
    <div>
      <h2>Lista de Usuarios</h2>
      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Usuario</th>
            <th>Fecha de Nacimiento</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id_usu}>
              <td>{user.id_usu}</td>
              <td>{user.nombre}</td>
              <td>{user.apellidos}</td>
              <td>{user.usuario}</td>
              <td>{user.fecha_nacimiento}</td>
              <td>
                <button onClick={() => deleteUser(user.id_usu)}>Eliminar</button>
                <button onClick={() => editUser(user.id_usu)}>Editar</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default UserTable;
