## Reto Backbone

- Lo primero que hice fue un analisis de la tabla de codigos postales de mexico. El cual a parti de ella genere las tablas correspondientes.
´Modelos: ZipCodes - Settlements - SettlementsTypes - Municipalities - FederalEntities´
- Luego genere un comando para importar los datos del txt que contiene los codigo postales de Mexico.
´php artisan import:zipcode´

- Por ultimo genere un indice en la tabla ZipCodes para el campo zip_code para optimizar las busquedas.
