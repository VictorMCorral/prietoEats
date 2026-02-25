FROM postgres:16

# Variables de entorno de PostgreSQL
ENV POSTGRES_DB=prieto_eats
ENV POSTGRES_USER=victor
ENV POSTGRES_PASSWORD=noteolvides01

# Puerto por defecto
EXPOSE 5432

# El contenedor arranca automáticamente postgres
CMD ["postgres"]


### Para construir la imagen, usa el siguiente comando en la terminal:
# docker build -t mi_postgres:1.0 .
### Para correr el contenedor, usa el siguiente comando en la terminal:
# docker run -d -p 5432:5432 --name contenedor_prietoEats mi_postgres:1.0
