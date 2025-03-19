import "./ListadoUsuarios.css";
import { useState, useEffect } from "react";
/* import {
  getAllUsuarios,
  createUsu,
  updateUsuById,
  deleteUsuById,
} from "../services/usuServer"; */


const FormUsuarios = ({ usu, saveUsu, onCancel }) => {
  const [id_usu, setId] = useState("");
  const [nombre, setNombre] = useState("");
  const [apellidos, setApellidos] = useState("");
  const [fechaNacimiento, setFechaNacimiento] = useState("");
  const [usuario, setUsuario] = useState("");
  const [password, setPassword] = useState("");

  const envioEvt = (e) => {
    e.preventDefault();
    const usuGuardar = {
      id_usu,
      nombre,
      apellidos,
      fecha_nacimiento: fechaNacimiento,
      usuario,
      contra: password,
    };
    saveUsu(usuGuardar);
  };
 

  const cancelarEvt = () => {
    if (onCancel) onCancel();
  };

  useEffect(() => {
    if (usu) {
      setId(usu.id_usu || 0);
      setNombre(usu.nombre || "");
      setApellidos(usu.apellidos || "");
      setFechaNacimiento(usu.fecha_nacimiento || "");
      setUsuario(usu.usuario || "");
      setPassword(usu.contra || "");
    }
  }, [usu]);
  
 

  return (
    <div className="form-container">
      <form onSubmit={envioEvt} className="user-form">
        <h2 className="form-title">{usu ? "Actualizar Usuario" : "Añadir Nuevo Usuario"}</h2>
        
        <div className="form-grid">
          <div className="form-group">
            <label htmlFor="id_usu">ID:</label>
            <input
              id="id_usu"
              type="number"
              value={id_usu}
              onChange={(e) => setId(e.target.value)}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label htmlFor="nombre">Nombre:</label>
            <input
              id="nombre"
              type="text"
              value={nombre}
              onChange={(e) => setNombre(e.target.value)}
              className="form-input"
              placeholder="Ingrese nombre"
            />
          </div>

          <div className="form-group">
            <label htmlFor="apellidos">Apellidos:</label>
            <input
              id="apellidos"
              type="text"
              value={apellidos}
              onChange={(e) => setApellidos(e.target.value)}
              className="form-input"
              placeholder="Ingrese apellidos"
            />
          </div>

          <div className="form-group">
            <label htmlFor="fechaNacimiento">Fecha de Nacimiento:</label>
            <input
              id="fechaNacimiento"
              type="date"
              value={fechaNacimiento}
              onChange={(e) => setFechaNacimiento(e.target.value)}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label htmlFor="usuario">Usuario:</label>
            <input
              id="usuario"
              type="text"
              value={usuario}
              onChange={(e) => setUsuario(e.target.value)}
              className="form-input"
              placeholder="Nombre de usuario"
            />
          </div>

          <div className="form-group">
            <label htmlFor="password">Contraseña:</label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="form-input"
              placeholder="Contraseña"
            />
          </div>
        </div>

        <div className="button-group">
          <button type="submit" className="btn-submit">
            {usu ? "Actualizar" : "Añadir"}
          </button>
          <button type="button" className="btn-cancel" onClick={cancelarEvt}>
            Cancelar
          </button>
        </div>
      </form>

      <style>{`
        .form-container {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          background-color: rgba(0, 0, 0, 0.5);
          z-index: 1000;
          padding: 20px;
        }

        .user-form {
          background-color: white;
          border-radius: 12px;
          padding: 32px;
          width: 100%;
          max-width: 700px;
          box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
          animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
          from {
            transform: translateY(-20px);
            opacity: 0;
          }
          to {
            transform: translateY(0);
            opacity: 1;
          }
        }

        .form-title {
          margin: 0 0 24px 0;
          color: #333;
          font-size: 24px;
          text-align: center;
          font-weight: bold;
        }

        .form-grid {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 20px;
        }

        .form-group {
          margin-bottom: 8px;
        }

        label {
          display: block;
          margin-bottom: 6px;
          font-weight: 500;
          color: #555;
        }

        .form-input {
          width: 100%;
          padding: 10px 12px;
          border: 1px solid #ddd;
          border-radius: 6px;
          font-size: 16px;
          transition: border-color 0.2s, box-shadow 0.2s;
          background-color: #f8f9fa;
        }

        .form-input:focus {
          border-color: #4299e1;
          box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
          outline: none;
          background-color: white;
        }

        .form-input::placeholder {
          color: #aaa;
        }

        .button-group {
          display: flex;
          justify-content: flex-end;
          gap: 12px;
          margin-top: 24px;
        }

        .btn-submit, .btn-cancel {
          padding: 10px 20px;
          border: none;
          border-radius: 6px;
          font-size: 16px;
          font-weight: 500;
          cursor: pointer;
          transition: background 0.2s, transform 0.1s;
        }

        .btn-submit {
          background-color: #3182ce;
          color: white;
        }

        .btn-submit:hover {
          background-color: #2b6cb0;
        }

        .btn-cancel {
          background-color: #e2e8f0;
          color: #4a5568;
        }

        .btn-cancel:hover {
          background-color: #cbd5e0;
        }

        .btn-submit:active, .btn-cancel:active {
          transform: translateY(1px);
        }

        @media (max-width: 640px) {
          .form-grid {
            grid-template-columns: 1fr;
          }
          
          .user-form {
            padding: 24px;
          }
        }
      `}</style>
    </div>
  );
};

export default FormUsuarios;