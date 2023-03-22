using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Data;
using Dapper;
using MySql.Data.MySqlClient;

namespace LukaKompControlPanel
{
    public class dataAccess
    {
        public static List<T> LoadData<T, U>(string sql, U parametri, string connectionString)
        {
            using (IDbConnection connection = new MySql.Data.MySqlClient.MySqlConnection(connectionString))
            {
                List<T> rows = connection.Query<T>(sql, parametri).ToList();

                return rows;
            }
        }

        public static async Task<List<T>> LoadDataAsync<T, U>(string sql, U parametri, string connectionString)
        {
            using (IDbConnection connection = new MySqlConnection(connectionString))
            {
                var rows = await connection.QueryAsync<T>(sql, parametri);
                
                return rows.ToList();
            }
        }

        public static void SaveData<T>(string sql, T parametri, string connectionString)
        {
            using (IDbConnection connection = new MySqlConnection(connectionString))
            {
                connection.Execute(sql, parametri);
            }
        }

        public static Task SaveDataAsync<T>(string sql, T parametri, string connectionString)
        {
            using (IDbConnection connection = new MySqlConnection(connectionString))
            {
                return connection.ExecuteAsync(sql, parametri);
            }
        }
    }
}
