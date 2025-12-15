import requests
import mysql.connector as mariadb
import pandas as pd
import parametros_2 as par
mariadb_connection = mariadb.connect(user=par.usuario, 
                                     password=par.password, 
                                     host=par.host, 
                                     port=par.puerto,
                                     database= par.bd)
create_cursor = mariadb_connection.cursor()
sql_statement= "SELECT id,razonsocial,nit FROM company WHERE estado=1"
create_cursor.execute(sql_statement)
results = create_cursor.fetchall()

create_cursor.close()
mariadb_connection.close()
columns = ['id', 'Empresa', 'nit']
df_companies = pd.DataFrame(results, columns=columns)

#creamos las columnas necesarias para almacenar los totales y faltantes
df_companies["Hacer_total"]=0
df_companies["Hacer_faltantes"]=0
df_companies["Planear_total"]=0
df_companies["Planear_faltantes"]=0
df_companies["Verificar_total"]=0
df_companies["Verificar_faltantes"]=0
df_companies["Actuar_total"]=0
df_companies["Actuar_faltantes"]=0

url = "https://sistegra.com.co/api/get-tracking-docs"

params =[
    {"company_id": 1,
    "module": "SG-SST",
    "sub_module": "hacer",
    "sub_items_to_skip[]": [1, 2, 3, 9, 30, 48, 79, 105, 122]
    },
    {"company_id": 1,
    "module": "SG-SST",
    "sub_module": "planear",
    "sub_items_to_skip[]": [30, 47, 50, 52, 57, 58, 59, 60, 62, 63, 93]
    },
    {"company_id": 1,
    "module": "SG-SST",
    "sub_module": "verificar",
    "sub_items_to_skip[]": [1,6]
    },
    {"company_id": 1,
    "module": "SG-SST",
    "sub_module": "actuar",
    "sub_items_to_skip[]": [1,6]
    },
    ]
print(df_companies.shape[0])

for i in range(len(df_companies)):
    print(i+1)
    for j in range(len(params)):
        params[j]["company_id"]=int(df_companies.loc[i]["id"])
        try:
            response = requests.get(url, params=params[j])
        except ValueError:
            continue
        try:
            response_dict = response.json()
        except :
            dict_final={"subitems_total":0,"faltan_total":0}
        dict_final=response_dict['totales']
        print(dict_final)
        if j==0:
            df_companies.at[i,"Hacer_total"]=dict_final["subitems_total"]
            df_companies.at[i,"Hacer_faltantes"]=dict_final["faltan_total"]
        elif j==1:
            df_companies.at[i,"Planear_total"]=dict_final["subitems_total"]
            df_companies.at[i,"Planear_faltantes"]=dict_final["faltan_total"]
        elif j==2:
            df_companies.at[i,"Verificar_total"]=dict_final["subitems_total"]
            df_companies.at[i,"Verificar_faltantes"]=dict_final["faltan_total"]
        else:
            df_companies.at[i,"Actuar_total"]=dict_final["subitems_total"]
            df_companies.at[i,"Actuar_faltantes"]=dict_final["faltan_total"]
df_companies.to_excel('avance_empresas.xlsx', index=False)