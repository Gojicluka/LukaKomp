using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

using LukaKompControlPanel.Models;
using LukaKompControlPanel.Klase;

namespace LukaKompControlPanel
{
    public partial class promenaKomponenata : Form
    {
        Point lastPoint;
        List<Komponenta> komponente = new List<Komponenta>();

        List<int> idoviZaMenjanje = new List<int>();

        public promenaKomponenata()
        {
            InitializeComponent();
        }

        private void promenaKomponenata_Load(object sender, EventArgs e)
        {
            komponente = dataAccess.LoadData<Komponenta, dynamic>(
               @"Select * from komponente",
               new { },
               Helper.CnnVal("LukaKomp"));

            for (int i = 0; i < komponente.Count; i++)
            {
                var index = dataGridView1.Rows.Add();
                dataGridView1.Rows[index].Cells["id"].Value = komponente[i].id;
                dataGridView1.Rows[index].Cells["ime"].Value = komponente[i].Ime;
                dataGridView1.Rows[index].Cells["cena"].Value = komponente[i].cena;
                dataGridView1.Rows[index].Cells["kolicina"].Value = komponente[i].kolicina;
            }

            dataGridView1.CellValueChanged += cellValueChanged;

            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 11f, FontStyle.Regular);
            }
            label1.Font = new Font(Design.fonts.Families[0], 25);
            button1.Font = new Font(Design.fonts.Families[0], 25);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            IDictionary<string, object> unosDictionary = new System.Dynamic.ExpandoObject();

            string idovi = "";
            string kolicinaCase = "";
            string cenaCase = "";
            for (int i = 0; i < idoviZaMenjanje.Count; i++)
            {
                if (i != 0) idovi += ",";

                unosDictionary[$"id{i}"] = dataGridView1.Rows[idoviZaMenjanje[i]].Cells["id"].Value;
                unosDictionary[$"cena{i}"] = dataGridView1.Rows[idoviZaMenjanje[i]].Cells["cena"].Value;
                unosDictionary[$"kolicina{i}"] = dataGridView1.Rows[idoviZaMenjanje[i]].Cells["kolicina"].Value;

                idovi += dataGridView1.Rows[idoviZaMenjanje[i]].Cells["id"].Value.ToString();
                kolicinaCase += $" WHEN @id{i} THEN @kolicina{i} ";
                cenaCase += $" WHEN @id{i} THEN @cena{i} ";
            }
            if (idovi != "")
            {
                dynamic unos = unosDictionary;
                dataAccess.SaveData<dynamic>($"UPDATE komponente SET kolicina = CASE id {kolicinaCase} END," +
                    $" cena = CASE id {cenaCase} END " +
                    $"WHERE id in ({idovi}) "
                    , unos, Helper.CnnVal("LukaKomp"));
                MessageBox.Show("Uspesno!");

            }
            this.Close();
        }

        private void textBox1_KeyPress(object sender, KeyPressEventArgs e)
        {
            string unos = textBox1.Text;

            for (int i = 0; i < dataGridView1.Rows.Count-1; i++)
            {
                if(dataGridView1.Rows[i].Cells["ime"].Value.ToString().ToLower().Contains(unos.ToLower()))
                {
                    dataGridView1.Rows[i].Visible = true;
                }
                else dataGridView1.Rows[i].Visible = false;
            }

        }

        private void cellValueChanged(object sender, DataGridViewCellEventArgs e)
        {
            if(!idoviZaMenjanje.Contains(e.RowIndex)) idoviZaMenjanje.Add(e.RowIndex);
        }

        private void promenaKomponenata_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void promenaKomponenata_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
