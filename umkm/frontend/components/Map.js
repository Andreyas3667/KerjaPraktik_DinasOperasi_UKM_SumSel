import { GoogleMap, Marker, useLoadScript } from "@react-google-maps/api";
import { useState, useEffect } from "react";
import axios from "axios";

const mapContainerStyle = {
  width: "100%",
  height: "500px",
};

const center = {
  lat: -3.319437, // Sesuaikan dengan lokasi awal (Sumatera Selatan)
  lng: 103.914399,
};

export default function Map() {
  const { isLoaded } = useLoadScript({
    googleMapsApiKey: process.env.NEXT_PUBLIC_GOOGLE_MAPS_API_KEY,
  });

  const [umkms, setUmkms] = useState([]);

  useEffect(() => {
    axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/umkm`)
      .then(response => setUmkms(response.data))
      .catch(error => console.error(error));
  }, []);

  if (!isLoaded) return <div>Loading...</div>;

  return (
    <GoogleMap mapContainerStyle={mapContainerStyle} zoom={8} center={center}>
      {umkms.map((umkm) => (
        <Marker key={umkm.id} position={{ lat: parseFloat(umkm.lat), lng: parseFloat(umkm.lng) }} />
      ))}
    </GoogleMap>
  );
}
