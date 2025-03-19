import React, { useState, useEffect } from 'react';
import { LineChart, Line, CartesianGrid, XAxis, YAxis, Tooltip, Legend } from 'recharts';
import { getLenta } from "../services/lentaServer";
import "./Lenta.css";

const EstadisticasLenta = () => {
  const [estadisticas, setEstadisticas] = useState({
    valorMedio: 0,
    valorMinimo: 0,
    valorMaximo: 0,
    datosGrafico: []
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [mes, setMes] = useState(new Date().getMonth() + 1);
  const [año, setAño] = useState(new Date().getFullYear());

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        // Obtener todos los datos de lenta (ahora incluyen fecha)
        const datosLenta = await getLenta();
        
        if (!datosLenta || datosLenta.length === 0) {
          throw new Error('No se recibieron datos');
        }
        
        // Filtrar datos por mes y año seleccionados
        const datosFiltrados = datosLenta.filter(item => {
          if (!item.fecha) return false;
          const fecha = new Date(item.fecha);
          return fecha.getMonth() + 1 === mes && fecha.getFullYear() === año;
        });

        // Si no hay datos para el mes y año seleccionados
        if (datosFiltrados.length === 0) {
          setEstadisticas({
            valorMedio: 0,
            valorMinimo: 0,
            valorMaximo: 0,
            datosGrafico: []
          });
          setLoading(false);
          return;
        }

        // Extraer solo los valores de lenta
        const valoresLenta = datosFiltrados.map(item => parseFloat(item.lenta));
        const valoresLentaNoNulos = valoresLenta.filter(valor => valor > 0);
        
        // Calcular estadísticas
        const valorMedio = valoresLentaNoNulos.length > 0 
          ? valoresLentaNoNulos.reduce((sum, val) => sum + val, 0) / valoresLentaNoNulos.length 
          : 0;
        
        const valorMinimo = valoresLentaNoNulos.length > 0 
          ? Math.min(...valoresLentaNoNulos) 
          : 0;
        
        const valorMaximo = valoresLentaNoNulos.length > 0 
          ? Math.max(...valoresLentaNoNulos) 
          : 0;

        // Formatear datos para el gráfico
        const datosGrafico = datosFiltrados.map(item => {
          const fecha = new Date(item.fecha);
          return {
            fecha: fecha.getDate().toString(), // Solo el día
            lenta: parseFloat(item.lenta),
            fechaCompleta: item.fecha // Guardamos la fecha completa para el tooltip
          };
        });

        // Ordenar por fecha
        datosGrafico.sort((a, b) => {
          const fechaA = new Date(a.fechaCompleta);
          const fechaB = new Date(b.fechaCompleta);
          return fechaA - fechaB;
        });

        setEstadisticas({
          valorMedio: valorMedio.toFixed(2),
          valorMinimo,
          valorMaximo,
          datosGrafico
        });
      } catch (err) {
        setError(err.message);
        console.error('Error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [mes, año]);

  // Selector de mes
  const handleMesChange = (e) => {
    setMes(parseInt(e.target.value));
  };

  // Selector de año
  const handleAñoChange = (e) => {
    setAño(parseInt(e.target.value));
  };

  // Componente personalizado para el tooltip
  const CustomTooltip = ({ active, payload, label }) => {
    if (active && payload && payload.length) {
      const data = payload[0].payload;
      const fecha = new Date(data.fechaCompleta);
      const formattedDate = fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
      
      return (
        <div className="custom-tooltip" style={{ backgroundColor: '#fff', padding: '10px', border: '1px solid #ccc' }}>
          <p className="label">{`Fecha: ${formattedDate}`}</p>
          <p className="value">{`Valor LENTA: ${payload[0].value}`}</p>
        </div>
      );
    }
  
    return null;
  };

  if (loading) {
    return (
      <div className="estadisticas-container">
        <h2>Estadísticas LENTA</h2>
        <div className="loading-spinner">
          <p>Cargando estadísticas...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="estadisticas-container">
        <h2>Estadísticas LENTA</h2>
        <div className="error-message">
          <p>Error al cargar las estadísticas: {error}</p>
        </div>
      </div>
    );
  }

  const meses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  ];

  const años = [];
  const currentYear = new Date().getFullYear();
  for (let i = currentYear - 5; i <= currentYear; i++) {
    años.push(i);
  }

  return (
    <div className="estadisticas-container">
      <h2>Estadísticas LENTA</h2>
      
      <div className="filtros">
        <div className="filtro-item">
          <label htmlFor="mes">Mes:</label>
          <select id="mes" value={mes} onChange={handleMesChange}>
            {meses.map((nombreMes, index) => (
              <option key={index + 1} value={index + 1}>
                {nombreMes}
              </option>
            ))}
          </select>
        </div>
        
        <div className="filtro-item">
          <label htmlFor="año">Año:</label>
          <select id="año" value={año} onChange={handleAñoChange}>
            {años.map((año) => (
              <option key={año} value={año}>
                {año}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className="tarjetas-estadisticas">
        <div className="tarjeta">
          <div className="tarjeta-header">
            <h3>Valor Medio</h3>
            <span className="icono-estadistica">📊</span>
          </div>
          <div className="tarjeta-valor">{estadisticas.valorMedio}</div>
        </div>
        
        <div className="tarjeta">
          <div className="tarjeta-header">
            <h3>Valor Mínimo</h3>
            <span className="icono-estadistica">⬇️</span>
          </div>
          <div className="tarjeta-valor">{estadisticas.valorMinimo}</div>
        </div>
        
        <div className="tarjeta">
          <div className="tarjeta-header">
            <h3>Valor Máximo</h3>
            <span className="icono-estadistica">⬆️</span>
          </div>
          <div className="tarjeta-valor">{estadisticas.valorMaximo}</div>
        </div>
      </div>

      <div className="grafico-container">
        <h3>Evolución del valor LENTA - {meses[mes - 1]} {año}</h3>
        <div className="grafico">
          {estadisticas.datosGrafico.length > 0 ? (
            <LineChart
              width={600}
              height={300}
              data={estadisticas.datosGrafico}
              margin={{
                top: 5,
                right: 30,
                left: 20,
                bottom: 5,
              }}
            >
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="fecha" />
              <YAxis domain={[0, 'auto']} />
              <Tooltip content={<CustomTooltip />} />
              <Legend />
              <Line
                type="monotone"
                dataKey="lenta"
                stroke="#8884d8"
                activeDot={{ r: 8 }}
                name="Valor LENTA"
              />
            </LineChart>
          ) : (
            <p>No hay datos disponibles para mostrar en el gráfico</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default EstadisticasLenta;