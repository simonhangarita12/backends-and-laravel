import pandas as pd
import mysql.connector as mariadb
import backend.parametros_2 as par
agregados_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="AGG")
quimicos_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TQC")
#concreto_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="RMX")
cemento_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="CEM")
geocycle_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="GEOCYCLE")
fundacion_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="FUNDACION")
transportadora_cemento_2024=pd.read_excel("2024 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TRANSCEM")
"""Agregamos una columna con el nombre de la empresa y otra con el id de la empresa"""
agregados_2024["Razon social"]="HOLCIM - AGREGADOS"
agregados_2024["id empresa"]=2671

quimicos_2024["Razon social"]="HOLCIM - QUIMICOS"
quimicos_2024["id empresa"]=2673

#concreto_2024["Razon social"]="HOLCIM - CONCRETO"
#concreto_2024["id empresa"]=2672

cemento_2024["Razon social"]="HOLCIM - CEMENTO"
cemento_2024["id empresa"]=2669

geocycle_2024["Razon social"]="GEOCYCLE LTDA"
geocycle_2024["id empresa"]=2641

fundacion_2024["Razon social"]="FUNDACIÓN HOLCIM COLOMBIA"
fundacion_2024["id empresa"]=2645

transportadora_cemento_2024["Razon social"]="TRANSCEM SAS"
transportadora_cemento_2024["id empresa"]=2642

#agrupamos en un solo dataframe
df_holcim_2024=pd.concat([agregados_2024,quimicos_2024,cemento_2024,geocycle_2024,fundacion_2024,transportadora_cemento_2024],ignore_index=True)
df_holcim_2024=df_holcim_2024.drop(columns=["OBSERVACIONES GENERALES"])
df_holcim_2024["Year"]=2024


agregados_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="AGG")
quimicos_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TQC")
concreto_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="RMX")
cemento_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="CEM")
geocycle_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="GEOCYCLE")
fundacion_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="FUNDACION")
transportadora_cemento_2023=pd.read_excel("2023 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TRANSCEM")
"""Agregamos una columna con el nombre de la empresa y otra con el id de la empresa"""
agregados_2023["Razon social"]="HOLCIM - AGREGADOS"
agregados_2023["id empresa"]=2671

quimicos_2023["Razon social"]="HOLCIM - QUIMICOS"
quimicos_2023["id empresa"]=2673

#concreto_2024["Razon social"]="HOLCIM - CONCRETO"
#concreto_2024["id empresa"]=2672

cemento_2023["Razon social"]="HOLCIM - CEMENTO"
cemento_2023["id empresa"]=2669

geocycle_2023["Razon social"]="GEOCYCLE LTDA"
geocycle_2023["id empresa"]=2641

fundacion_2023["Razon social"]="FUNDACIÓN HOLCIM COLOMBIA"
fundacion_2023["id empresa"]=2645

transportadora_cemento_2023["Razon social"]="TRANSCEM SAS"
transportadora_cemento_2023["id empresa"]=2642

#agrupamos en un solo dataframe
df_holcim_2023=pd.concat([agregados_2023,quimicos_2023,cemento_2023,geocycle_2023,fundacion_2023,transportadora_cemento_2023],ignore_index=True)
df_holcim_2023=df_holcim_2023.drop(columns=["PELIGRO"])
df_holcim_2023["Year"]=2023

agregados_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="AGG")
quimicos_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TQC")
concreto_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="RMX")
cemento_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="CEM")
geocycle_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="GEOCYCLE")
fundacion_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="FUNDACION")
transportadora_cemento_2022=pd.read_excel("2022 - Actualizacion Requisitos Legales H&S (1).xlsx",sheet_name="TRANSCEM")
"""Agregamos una columna con el nombre de la empresa y otra con el id de la empresa"""
agregados_2022["Razon social"]="HOLCIM - AGREGADOS"
agregados_2022["id empresa"]=2671

quimicos_2022["Razon social"]="HOLCIM - QUIMICOS"
quimicos_2022["id empresa"]=2673

#concreto_2024["Razon social"]="HOLCIM - CONCRETO"
#concreto_2024["id empresa"]=2672

cemento_2022["Razon social"]="HOLCIM - CEMENTO"
cemento_2022["id empresa"]=2669

geocycle_2022["Razon social"]="GEOCYCLE LTDA"
geocycle_2022["id empresa"]=2641

fundacion_2022["Razon social"]="FUNDACIÓN HOLCIM COLOMBIA"
fundacion_2022["id empresa"]=2645

transportadora_cemento_2022["Razon social"]="TRANSCEM SAS"
transportadora_cemento_2022["id empresa"]=2642

#agrupamos en un solo dataframe
df_holcim_2022=pd.concat([agregados_2022,quimicos_2022,cemento_2022,geocycle_2022,fundacion_2022,transportadora_cemento_2022],ignore_index=True)
df_holcim_2022=df_holcim_2022.drop(columns=["PELIGRO"])
df_holcim_2022["Year"]=2022

df_total_holcim=pd.concat([df_holcim_2024,df_holcim_2023,df_holcim_2022],ignore_index=True)
columns_order=["id empresa","Year","NORMA","ASUNTO","ARTÍCULO","REQUISITO LEGAL",
               "EVIDENCIA DE CUMPLIMIENTO","CUMPLIMIENTO SEGMENTO DEL REQUISITO "]
df_total_holcim=df_total_holcim[columns_order]
df_total_holcim=df_total_holcim.fillna("")


def enviar_datos(filt:pd.DataFrame,user=par.usuario, password=par.password, host=par.host, port=par.puerto, database=par.bd):
    """En esta función vamos a enviar los datos despues de pasar por el proceso de limpieza y
    preprocesamiento a la base de datos, en nuestra tabla donde contenemos la información ya limpia

    Args:
        filt(DataFrame): DataFrame con la información ya limpia y lista para ser enviada a la base de datos
        user(str): usuario de la base de datos
        password(str): contraseña de la base de datos
        host(str): host de la base de datos
        port(int): puerto de la base de datos
        database(str): nombre de la base de datos"""

    mariadb_connection = mariadb.connect(user=user,
                                         password=password,
                                         host=host,
                                         port=port,
                                         database=database)
    create_cursor = mariadb_connection.cursor()
    
    for index, row in filt.iterrows():
        print("Insertando fila ", index+1)
        try:
            create_cursor.execute("""
                INSERT IGNORE INTO requisitos_legales_historico (
                    id_empresa, year, norma,asunto, articulo,requisito_legal,
                    evidencia_de_cumplimiento,cumplimiento_segmento_del_requisito,estado
                    
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                int(row['id empresa']), int(row['Year']), str(row['NORMA']), str(row['ASUNTO']),
                str(row['ARTÍCULO']),str(row['REQUISITO LEGAL']),str(row["EVIDENCIA DE CUMPLIMIENTO"]),
                str(row['CUMPLIMIENTO SEGMENTO DEL REQUISITO ']),1

            ))
        
        except mariadb.Error as e:
            print("el error es: ", e)

    try:
        mariadb_connection.commit()
        print("Datos insertados correctamente")
    except mariadb.Error as e:
        print("el error es: ", e)
    finally:
        create_cursor.close()
        mariadb_connection.close()

enviar_datos(df_total_holcim)

       