using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Drawing.Text;
using System.Runtime.InteropServices;
namespace LukaKompControlPanel.Klase
{
    public class Design
    {
        public static PrivateFontCollection fonts = new PrivateFontCollection();
        
        static Design()
        {
            int fontLength = Properties.Resources.Adam_Medium.Length;
            byte[] fontdata = Properties.Resources.Adam_Medium;
            System.IntPtr data = Marshal.AllocCoTaskMem(fontLength);
            Marshal.Copy(fontdata, 0, data, fontLength);

            fonts.AddMemoryFont(data, fontLength);
        }
        
    }
}
