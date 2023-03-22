using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LukaKompControlPanel.Models
{
    public class korisnik
    {
        public int id{ get; set; }
        public string username { get; set; }
        public string email { get; set; }
        public string privilegija { get; set; }
        public string sifra { get; set; }

        
        public string FullInfo
        {
            get { return $"{id} | {username} | {email} | {privilegija}"; }
        }
        
    }
}
