import pandas as pd
#cuando yo coloco as es un apodo
import mysql.connector as mariadb
import parametros_3 as par
import re
df_holcim=pd.read_excel("matriz.xlsx",sheet_name="Requisitos Generales")
print(df_holcim.columns)
#Aca tomamos las columnas que queramos, como si estuvieramos llamando un diccionario. las optras se ignoran
df_holcim=df_holcim[["SUBCLASIFICADOR","NORMAS (Objeto)","año "," Artículos (si aplica)","CRITERIOS  PARA AUTOEVALUACIÓN\nQUE EVIDENCIAN EL CUMPLIMIENTO LEGAL\n"]]
#aca renombreamos
df_holcim=df_holcim.rename(columns={"NORMAS (Objeto)":"NORMA","año ":"FECHA"," Artículos (si aplica)":"ARTICULO","CRITERIOS  PARA AUTOEVALUACIÓN\nQUE EVIDENCIAN EL CUMPLIMIENTO LEGAL\n":"CRITERIOS"})
#añadimos el id que tendra al ingresarlo en la base de datos (esto es un parche y sólo funciona en este cargue)
df_holcim["id_norma"]=df_holcim.index+1+9455


df_criterios=pd.DataFrame(columns=["id_norma","criterio","valor"])
for i in range(len(df_holcim)):
    criterios=df_holcim.loc[i,"CRITERIOS"]
    criterios=criterios.replace("\n","")
    #Vamos a crear un patron con regex para capturar el separador de cada criterio que en este caso es un numero, un punto 
    # y un espacio ej. "14. " para reemplazarlo con algo que nos permita hacer un split facilmente
    pattern=r"(\d+\.\s)"
    #sustituimos por un patron raro que nos asegure con gran probabilidad que no aparece en el texto
    criterios= re.sub(pattern, '///&&&', criterios)
    #un split es meter a una lista los valores tal que se separen por el separador especificado
    criterios_lista=criterios.split("///&&&")
    criterios_lista=criterios_lista[1:]
    id=df_holcim.loc[i,"id_norma"]
    if len(criterios_lista)>0:
        valor=round(5/len(criterios_lista),2)
        for criterio in criterios_lista:
            new_row = pd.DataFrame([{
                "id_norma": id,
                "criterio": criterio,
                "valor": valor
            }])
            #.concat es un metodo para pegar dos dataframes que tengan la misma estructura (columnas)

            df_criterios = pd.concat([df_criterios, new_row], ignore_index=True)

df_criterios["modulo"]=2
df_criterios["estado"]=1
#df_criterios.to_excel("criterios_holcim.xlsx",index=False)
#fillna sirve para rellenar vacios
df_criterios=df_criterios.fillna(0)
df_requisitos=df_holcim[["id_norma","FECHA","NORMA","ARTICULO","SUBCLASIFICADOR"]].copy()
df_requisitos["ESTADO"]=1
df_requisitos["ID_EMPRESA"]=2645
df_requisitos=df_requisitos.fillna("")
#pasamos la fecha a un formato reconocible en sql
df_requisitos["FECHA"] = (
    pd.to_datetime(df_requisitos["FECHA"].astype(str) + "-01-01")
)
#df_requisitos.to_excel("requisitos_holcim.xlsx",index=False)
df_int_legal=df_holcim[["id_norma"]].copy()
df_int_legal["modulo"]=2
df_int_legal["estado"]=1

try:
    mariadb_connection = mariadb.connect(user=par.usuario,password=par.password,host=par.host,port=par.puerto,database=par.bd)
    if mariadb_connection.is_connected():
        cursor = mariadb_connection.cursor()
        for row in df_requisitos.to_dict(orient="records"): 
            sql_statement="""INSERT INTO tbl_requisitos_legales (id,id_empresa,descripcion_norma,fecha_emision,articulos_aplicables,subclasificacion,estado) VALUES (%(id_norma)s,%(ID_EMPRESA)s,%(NORMA)s,%(FECHA)s,%(ARTICULO)s,%(SUBCLASIFICADOR)s,%(ESTADO)s)"""
            cursor.execute(sql_statement,row)
        mariadb_connection.commit() 

        for row in df_criterios.to_dict(orient="records"):
            sql_statement="""INSERT INTO tbl_criterio_cumplimiento (id_requisito_legal,id_modulo,criterio,valor,estado) VALUES (%(id_norma)s,%(modulo)s,%(criterio)s,%(valor)s,%(estado)s)"""
            cursor.execute(sql_statement, row)
        mariadb_connection.commit()
        
        for row in df_int_legal.to_dict(orient="records"):
            sql_statement="""INSERT INTO tbl_int_legales (id_modLegal,id_norma,estado) VALUES (%(modulo)s,%(id_norma)s,%(estado)s)"""
            cursor.execute(sql_statement, row)
        mariadb_connection.commit()
except mariadb.Error as e:
    print(f"Error connecting to MariaDB Platform: {e}")
finally:
    cursor.close()
    mariadb_connection.close()


