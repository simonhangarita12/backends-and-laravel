import parametros_2 as par
import mysql.connector as mariadb
import pandas as pd
from datetime import datetime as dt
from fastapi import FastAPI, HTTPException, Depends, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
def extraer_informacion(id_empresa):
    """Extrae de la base de datos la información de colaboradores en la empresa seleccionada desde la página
    Args:
        id_empresa (int): id de la empresa seleccionada en la página
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada"""
    mariadb_connection = mariadb.connect(user=par.usuario,
                                        password=par.password,
                                        host=par.host,
                                        port=par.puerto,
                                        database=par.bd)
    create_cursor = mariadb_connection.cursor()
    sql_statement = """SELECT   cp.razonsocial,rl.id_tipo_peligro,rl.total,rl.totalCriterios FROM tbl_requisitos_legales rl LEFT JOIN company cp ON rl.id_empresa=cp.id WHERE rl.id_empresa = {}""".format(id_empresa)
    create_cursor.execute(sql_statement)
    result = create_cursor.fetchall()
    columns=['Empresa','Tipo de peligro','puntaje','Total de criterios']
    df= pd.DataFrame(result, columns=columns)
    df["puntaje"]=df["puntaje"].fillna(0)
    df["puntaje"]=df["puntaje"].apply(lambda x:float(x))
    df["Total de criterios"]=df["Total de criterios"].fillna(0)
    df["Total de criterios"]=df["Total de criterios"].apply(lambda x:int(x))
    mariadb_connection.close()
    return df
def extraer_informacion_multiple(id_empresas):
    """Extrae de la base de datos la información de colaboradores en la empresa seleccionada desde la página
    Args:
        id_empresa (int): id de la empresa seleccionada en la página
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada"""
    mariadb_connection = mariadb.connect(user=par.usuario,
                                        password=par.password,
                                        host=par.host,
                                        port=par.puerto,
                                        database=par.bd)
    create_cursor = mariadb_connection.cursor()
    sql_statement = """SELECT   cp.razonsocial,rl.id_tipo_peligro,rl.total,rl.totalCriterios FROM tbl_requisitos_legales rl LEFT JOIN company cp ON rl.id_empresa=cp.id WHERE rl.id_empresa IN {}""".format(tuple(id_empresas))
    create_cursor.execute(sql_statement)
    result = create_cursor.fetchall()
    columns=['Empresa','Tipo de peligro','puntaje','Total de criterios']
    df= pd.DataFrame(result, columns=columns)
    df["puntaje"]=df["puntaje"].fillna(0)
    df["puntaje"]=df["puntaje"].apply(lambda x:float(x))
    df["Total de criterios"]=df["Total de criterios"].fillna(0)
    df["Total de criterios"]=df["Total de criterios"].apply(lambda x:int(x))
    mariadb_connection.close()
    return df
def conversion_variables(df):
    """Realizamos la conversión de las variables númericas a categoricas para poder trabajar mostrar la información
    con una mayor claridad.
    Args:
        df (pd.dataframe): dataframe con la información de las normas
    Returns:
        df (pd.dataframe): dataframe con la información de las normas con las 
                           variables convertidas a categoricas y datetime según corresponda"""
    df['Tipo de peligro']=df.apply(lambda x:par.diccionario_tipo_peligro[int(x['Tipo de peligro'])],axis=1)
    return df
df_prueba=extraer_informacion(2645)
df_prueba=conversion_variables(df_prueba)
#Hacemos lo mismo para cargar la información del 2024 hacia atras
def extraer_informacion_historico(id_empresa,year):
    """Extrae de la base de datos la información de colaboradores en la empresa seleccionada desde la página
    Args:
        id_empresa (int): id de la empresa seleccionada en la página
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada"""
    mariadb_connection = mariadb.connect(user=par.usuario,
                                        password=par.password,
                                        host=par.host,
                                        port=par.puerto,
                                        database=par.bd)
    create_cursor = mariadb_connection.cursor()
    sql_statement = """SELECT   cp.razonsocial,rl.cumplimiento_segmento_del_requisito FROM requisitos_legales_historico rl LEFT JOIN company cp ON rl.id_empresa=cp.id WHERE rl.id_empresa = %s AND rl.year = %s"""
    create_cursor.execute(sql_statement, (id_empresa, year))
    result = create_cursor.fetchall()
    columns=['Empresa','cumplimiento']
    df= pd.DataFrame(result, columns=columns)
    def determinar_puntaje(cumplimiento):
        try:
            if cumplimiento=="Si cumple":
                return 5
            elif cumplimiento=="No aplica":
                return -1
            else:
                return 0
        except:
            return 0
    df["puntaje"]=df["cumplimiento"].apply(determinar_puntaje)
    mariadb_connection.close()
    return df
def extraer_informacion_multiple_historico(id_empresas,year):
    """Extrae de la base de datos la información de colaboradores en la empresa seleccionada desde la página
    Args:
        id_empresa (int): id de la empresa seleccionada en la página
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada"""
    mariadb_connection = mariadb.connect(user=par.usuario,
                                        password=par.password,
                                        host=par.host,
                                        port=par.puerto,
                                        database=par.bd)
    create_cursor = mariadb_connection.cursor()
    placeholders = ','.join(['%s'] * len(id_empresas))
    sql_statement = f"""
        SELECT cp.razonsocial, rl.cumplimiento_segmento_del_requisito
        FROM requisitos_legales_historico rl
        LEFT JOIN company cp ON rl.id_empresa = cp.id
        WHERE rl.id_empresa IN ({placeholders})
        AND rl.year = %s
    """
    params = tuple(id_empresas) + (year,)
    create_cursor.execute(sql_statement, params)
    result = create_cursor.fetchall()
    columns=['Empresa','cumplimiento']
    df= pd.DataFrame(result, columns=columns)
    def determinar_puntaje(cumplimiento):
        try:
            if cumplimiento=="Si cumple":
                return 5
            elif cumplimiento=="No aplica":
                return -1
            else:
                return 0
        except:
            return 0
    df["puntaje"]=df["cumplimiento"].apply(determinar_puntaje)
    mariadb_connection.close()
    return df


app=FastAPI()
origins = [
    "http://localhost:5173",
    "http://190.249.137.176:65080",
    "http://172.16.100.11"
]
app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/api/puntaje/total")
async def get_total(id_empresas: list[int] = Query(["2645"])):
    if len(id_empresas)==1:
        df=extraer_informacion(id_empresas[0])
        company_name=df['Empresa'].values[0]
    else:
        df=extraer_informacion_multiple(id_empresas)
        company_name="Varias empresas"
    if df.empty:
        return {"value":0}
    df=conversion_variables(df)
    #Colocamos una columna para el valor total que debe tener en puntaje idealmente
    df['Total']=5
    puntaje_total=df['puntaje'].sum()
    total=df['Total'].sum()
    por_centaje=100*(puntaje_total/total)
    total_requisitos=df["Total de criterios"].sum()
    def requisitos_completados():
        try:
            acumulado=0
            for i in range(len(df)):
                acumulado+=int(df.loc[i,"puntaje"]*df.loc[i,"Total de criterios"]/df.loc[i,"Total"])
            return acumulado
        except:
            return 0

    requisitos_completados=requisitos_completados()
    return {"value":round(por_centaje,3),
            "company_name":company_name,
            "total_requisitos":int(total_requisitos),
            "requisitos_completados":int(requisitos_completados)}


@app.get("/api/puntaje/union")
async def get_union(id_empresas: list[int] = Query(["2645"])):
    if len(id_empresas)==1:
        df=extraer_informacion(id_empresas[0])
    else:
        df=extraer_informacion_multiple(id_empresas)
    lista_empresas=[2645,2641,2671,2669,2672,2673,2674,2642]
    if df.empty:
        return {"value":0}
    df=conversion_variables(df)
    df['puntaje'] = df['puntaje'].fillna(0)
    df['Total de criterios'] = df['Total de criterios'].fillna(0)
    #Definimos las normas que se han completado en su totalidad
    df['Completado']=df.apply(lambda x:"La norma se encuentra cumplida en su totalidad" if abs(x['puntaje'] - 5) <= 0.01 else 
                              "La norma no se encuentra cumplida en su totalidad",axis=1)
    df["Contador"]=1
    df_group_suma=df.groupby(['Completado'])[["Contador"]].sum().reset_index()
    df_group_suma=df_group_suma.rename(columns={"Contador":"Suma"})
    # Realizamos una función de agregación para el porcentaje
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(df)),3)
        except:
            return 0
    
    df_group_porcentaje=df.groupby(["Completado"])["Contador"].apply(porcentaje).reset_index()
    df_group_porcentaje=df_group_porcentaje.rename(columns={"Contador":"Porcentaje"})
    df_group=pd.merge(df_group_suma,df_group_porcentaje,on="Completado",how="left")
    data_list = []
    df_group=df_group.sort_values(by="Completado",ascending=False).reset_index(drop=True)
    for i in range(len(df_group)):
        data_list.append({
            "name": df_group.loc[i, "Completado"],
            "y": float(df_group.loc[i, "Porcentaje"]),  
            "total": int(df_group.loc[i, "Suma"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/puntaje/empresa")
async def get_puntaje_empresa(id_empresas: list[int] = Query(["2645"])):
    """if len(id_empresas)==1:
        df=extraer_informacion(id_empresas[0])
    else:
        df=extraer_informacion_multiple(id_empresas)"""
    lista_empresas=[2645,2641,2671,2669,2672,2673,2674,2642]
    df=extraer_informacion_multiple(lista_empresas)
    if df.empty:
        return {"kpis":[]}
    df=conversion_variables(df)
    df['puntaje'] = df['puntaje'].fillna(0)
    df['Numero de normas']=1
    df["Numero de normas completadas"]=df.apply(lambda x: 1 if abs(x['puntaje'] - 5) <= 0.01 else 0,axis=1)
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(x)),3)
        except:
            return 0
    
    df_group_porcentaje=df.groupby(["Empresa"])["Numero de normas completadas"].apply(porcentaje).reset_index()
    df_group_porcentaje=df_group_porcentaje.rename(columns={"Numero de normas completadas":"Porcentaje de normas cumplidas"})
    df_group_suma=df.groupby(['Empresa'])[["Numero de normas","Numero de normas completadas"]].sum().reset_index()
    df_group=pd.merge(df_group_suma,df_group_porcentaje,on="Empresa",how="left")
    name_list=[]
    data_list = []
    data_incomplete=[]
    for i in range(len(df_group)):
        name_list.append(str(df_group.loc[i, "Empresa"]))
        data_list.append({"y":float(df_group.loc[i, "Porcentaje de normas cumplidas"]),
                          "total_normas":int(df_group.loc[i, "Numero de normas"]),
                          "normas_cumplidas":int(df_group.loc[i, "Numero de normas completadas"])})
        data_incomplete.append({"y":float(100-df_group.loc[i, "Porcentaje de normas cumplidas"]),
                          "total_normas":int(df_group.loc[i, "Numero de normas"]),
                          "normas_cumplidas":int(df_group.loc[i, "Numero de normas"]-df_group.loc[i, "Numero de normas completadas"])})
    
    datos = {
        "categories": name_list,
        "series": [
            {
            "name": "No Cumplido",
            "color": '#ff6767',
            "data": data_incomplete
            },
            {
            "name": "Cumplido",
            "color": "#4fbe88",
            "data": data_list
            }
        ]
    }
    return datos
@app.get("/api/puntaje/segmentacion")
async def get_segmentacion(id_empresas: list[int] = Query(["2645"])):
    if len(id_empresas)==1:
        df=extraer_informacion(id_empresas[0])
    else:
        df=extraer_informacion_multiple(id_empresas)
    
    if df.empty:
        return {"kpis":[]}
    df=conversion_variables(df)
    df['puntaje'] = df['puntaje'].fillna(0)
    df['Numero de normas']=1
    df["Numero de normas completadas"]=df.apply(lambda x: 1 if abs(x['puntaje'] - 5) <= 0.01 else 0,axis=1)
    df["Numero de normas completadas parcialmente"]=df.apply(lambda x: 1 if (abs(x['puntaje'] - 5)> 0.01 and abs(x['puntaje'] - 5) < 5)  else 0,axis=1)
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(x)),3)
        except:
            return 0
    df_group_suma=df.groupby(['Empresa'])[["Numero de normas","Numero de normas completadas","Numero de normas completadas parcialmente"]].sum().reset_index()
    

    data_list = []
    data_incomplete=[]
    data_parcial=[]
    numero_normas_completadas=df_group_suma["Numero de normas completadas"].sum()
    numero_normas_parciales=df_group_suma["Numero de normas completadas parcialmente"].sum()
    numero_normas=df_group_suma["Numero de normas"].sum()
    porcentaje_normas_completadas=porcentaje(df["Numero de normas completadas"])
    porcentaje_normas_parciales=porcentaje(df["Numero de normas completadas parcialmente"])
    data_list.append({"y":float(porcentaje_normas_completadas),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas_completadas)})
    data_parcial.append({"y":float(porcentaje_normas_parciales),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas_parciales)})
    data_incomplete.append({"y":float(100-porcentaje_normas_completadas-porcentaje_normas_parciales),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas-numero_normas_completadas-numero_normas_parciales)})
    
    datos = {
        "series": [
            {
            "name": "No Cumplido",
            "color": '#ff6767',
            "data": data_incomplete
            },
            {
            "name": "Cumplido",
            "color": "#4fbe88",
            "data": data_list
            },
            {
            "name": "Cumplido parcialmente",
            "color": "#DDDF0D",
            "data": data_parcial
            }

        ]
    }
    return datos
@app.get("/api/puntaje/total/historico")
async def get_total(id_empresas: list[int] = Query(["2645"]),year: int = 2022):
    if len(id_empresas)==1:
        df=extraer_informacion_historico(id_empresas[0],year)
        company_name=df['Empresa'].values[0]
    else:
        df=extraer_informacion_multiple_historico(id_empresas,year)
        company_name="Varias empresas"
    if df.empty:
        return {"value":0}
    #Colocamos una columna para el valor total que debe tener en puntaje idealmente
    df=df[df['puntaje']!=-1]
    df['Total']=5
    puntaje_total=df['puntaje'].sum()
    total=df['Total'].sum()
    por_centaje=100*(puntaje_total/total)

    

    return {"value":round(por_centaje,3),
            "company_name":company_name,
            }

@app.get("/api/puntaje/union/historico")
async def get_union(id_empresas: list[int] = Query(["2645"]),year: int = 2022):
    if len(id_empresas)==1:
        df=extraer_informacion_historico(id_empresas[0],year)
    else:
        df=extraer_informacion_multiple_historico(id_empresas,year)
    lista_empresas=[2645,2641,2671,2669,2672,2673,2674,2642]
    if df.empty:
        return {"value":0}
    
    df['puntaje'] = df['puntaje'].fillna(0)
    #Definimos las normas que se han completado en su totalidad
    df['Completado']=df.apply(lambda x:"La norma se encuentra cumplida en su totalidad" if abs(x['puntaje'] - 5) <= 0.01 else 
                              "La norma no se encuentra cumplida en su totalidad",axis=1)
    df["Contador"]=1
    df_group_suma=df.groupby(['Completado'])[["Contador"]].sum().reset_index()
    df_group_suma=df_group_suma.rename(columns={"Contador":"Suma"})
    # Realizamos una función de agregación para el porcentaje
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(df)),3)
        except:
            return 0
    
    df_group_porcentaje=df.groupby(["Completado"])["Contador"].apply(porcentaje).reset_index()
    df_group_porcentaje=df_group_porcentaje.rename(columns={"Contador":"Porcentaje"})
    df_group=pd.merge(df_group_suma,df_group_porcentaje,on="Completado",how="left")
    data_list = []
    df_group=df_group.sort_values(by="Completado",ascending=False).reset_index(drop=True)
    for i in range(len(df_group)):
        data_list.append({
            "name": df_group.loc[i, "Completado"],
            "y": float(df_group.loc[i, "Porcentaje"]),  
            "total": int(df_group.loc[i, "Suma"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos

@app.get("/api/puntaje/empresa/historico")
async def get_puntaje_empresa(id_empresas: list[int] = Query(["2645"]),year:int=2022):
    """if len(id_empresas)==1:
        df=extraer_informacion_historico(id_empresas[0],year)
    else:
        df=extraer_informacion_multiple_historico(id_empresas,year)"""
    lista_empresas=[2645,2641,2671,2669,2672,2673,2674,2642]
    df=extraer_informacion_multiple_historico(lista_empresas,year)
    if df.empty:
        return {"kpis":[]}
    df['puntaje'] = df['puntaje'].fillna(0)
    df['Numero de normas']=1
    df["Numero de normas completadas"]=df.apply(lambda x: 1 if abs(x['puntaje'] - 5) <= 0.01 else 0,axis=1)
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(x)),3)
        except:
            return 0
    
    df_group_porcentaje=df.groupby(["Empresa"])["Numero de normas completadas"].apply(porcentaje).reset_index()
    df_group_porcentaje=df_group_porcentaje.rename(columns={"Numero de normas completadas":"Porcentaje de normas cumplidas"})
    df_group_suma=df.groupby(['Empresa'])[["Numero de normas","Numero de normas completadas"]].sum().reset_index()
    df_group=pd.merge(df_group_suma,df_group_porcentaje,on="Empresa",how="left")
    name_list=[]
    data_list = []
    data_incomplete=[]
    for i in range(len(df_group)):
        name_list.append(str(df_group.loc[i, "Empresa"]))
        data_list.append({"y":float(df_group.loc[i, "Porcentaje de normas cumplidas"]),
                          "total_normas":int(df_group.loc[i, "Numero de normas"]),
                          "normas_cumplidas":int(df_group.loc[i, "Numero de normas completadas"])})
        data_incomplete.append({"y":float(100-df_group.loc[i, "Porcentaje de normas cumplidas"]),
                          "total_normas":int(df_group.loc[i, "Numero de normas"]),
                          "normas_cumplidas":int(df_group.loc[i, "Numero de normas"]-df_group.loc[i, "Numero de normas completadas"])})
    
    datos = {
        "categories": name_list,
        "series": [
            {
            "name": "No Cumplido",
            "color": '#ff6767',
            "data": data_incomplete
            },
            {
            "name": "Cumplido",
            "color": "#4fbe88",
            "data": data_list
            }
        ]
    }
    return datos
@app.get("/api/puntaje/segmentacion/historico")
async def get_segmentacion(id_empresas: list[int] = Query(["2645"]),year: int = 2022):
    if len(id_empresas)==1:
        df=extraer_informacion_historico(id_empresas[0],year)
    else:
        df=extraer_informacion_multiple_historico(id_empresas,year)
    
    if df.empty:
        return {"kpis":[]}
    df['puntaje'] = df['puntaje'].fillna(0)
    df['Numero de normas']=1
    df["Numero de normas completadas"]=df.apply(lambda x: 1 if abs(x['puntaje'] - 5) <= 0.01 else 0,axis=1)
    df["Numero de normas completadas parcialmente"]=df.apply(lambda x: 1 if (abs(x['puntaje'] - 5)> 0.01 and abs(x['puntaje'] - 5) < 5)  else 0,axis=1)
    def porcentaje(x):
        try:
            return round(100*(x.sum()/len(x)),3)
        except:
            return 0
    df_group_suma=df.groupby(['Empresa'])[["Numero de normas","Numero de normas completadas","Numero de normas completadas parcialmente"]].sum().reset_index()
    

    data_list = []
    data_incomplete=[]
    data_parcial=[]
    numero_normas_completadas=df_group_suma["Numero de normas completadas"].sum()
    numero_normas_parciales=df_group_suma["Numero de normas completadas parcialmente"].sum()
    numero_normas=df_group_suma["Numero de normas"].sum()
    porcentaje_normas_completadas=porcentaje(df["Numero de normas completadas"])
    porcentaje_normas_parciales=porcentaje(df["Numero de normas completadas parcialmente"])
    data_list.append({"y":float(porcentaje_normas_completadas),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas_completadas)})
    data_parcial.append({"y":float(porcentaje_normas_parciales),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas_parciales)})
    data_incomplete.append({"y":float(100-porcentaje_normas_completadas-porcentaje_normas_parciales),
                        "total_normas":int(numero_normas),
                        "normas_cumplidas":int(numero_normas-numero_normas_completadas-numero_normas_parciales)})
    
    datos = {
        "series": [
            {
            "name": "No Cumplido",
            "color": '#ff6767',
            "data": data_incomplete
            },
            {
            "name": "Cumplido",
            "color": "#4fbe88",
            "data": data_list
            },
            {
            "name": "Cumplido parcialmente",
            "color": "#DDDF0D",
            "data": data_parcial
            }

        ]
    }
    return datos
