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
    public partial class Privilegije : Form
    {
        Point lastPoint;
        List<korisnik> korisnici = new List<korisnik>();

        public Privilegije()
        {
            InitializeComponent();
        }

        private void Privilegije_Load(object sender, EventArgs e)
        {
            ucitaj("%");

            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 11f, FontStyle.Regular);
            }
            label1.Font = new Font(Design.fonts.Families[0], 25);
            button1.Font = new Font(Design.fonts.Families[0], 25);
        }

        private async void ucitaj(string param)
        {
            /*
            dataGridView1.Rows.Clear();
            dataGridView1.Refresh();
            */
            korisnici = await dataAccess.LoadDataAsync<korisnik, dynamic>(
                "select * from korisnik where username like @param",
                new { param=param },
                Helper.CnnVal("LukaKomp"));

            //dataGridView1.DataSource = korisnici;
            for (int i = 0; i < korisnici.Count; i++)
            {
                var index = dataGridView1.Rows.Add();
                //DataGridViewRow row = (DataGridViewRow)dataGridView1.Rows[0].Clone();
                dataGridView1.Rows[index].Cells["ID"].Value = korisnici[i].id;
                dataGridView1.Rows[index].Cells["Username"].Value = korisnici[i].username;
                dataGridView1.Rows[index].Cells["Email"].Value = korisnici[i].email;
                dataGridView1.Rows[index].Cells["trenutnaPrivilegija"].Value = korisnici[i].privilegija;
                //dataGridView1.Rows[index].Cells["NovaPrivilegija"].Items.Add =
                
                ComboBox cb = new ComboBox();
                cb.Items.Add("");
                cb.Items.Add("admin");
                cb.Items.Add("user");
                int trenutniIndex = index;
                //cb.SelectedIndexChanged += (sender, EventArgs) => { comboBoxChanged(sender, EventArgs, (index )); }; ;
                ((DataGridViewComboBoxColumn)dataGridView1.Columns["NovaPrivilegija"]).DataSource = cb.Items;
            }
            dataGridView1.CellValueChanged += cellValueChanged;
        }


        private async void cellValueChanged(object sender, DataGridViewCellEventArgs e)
        {
            //ComboBox cmbBox = e.Control as ComboBox;
            DataGridViewComboBoxCell cb = (DataGridViewComboBoxCell)dataGridView1.Rows[e.RowIndex].Cells["NovaPrivilegija"];
            //MessageBox.Show(cmbBox.SelectedValue.ToString());
            if (e.ColumnIndex==4)
            {
                await dataAccess.SaveDataAsync<dynamic>("update korisnik set privilegija=@param where id=@param2",
                new {
                    param = cb.Value.ToString(),
                    param2 = dataGridView1.Rows[e.RowIndex].Cells["ID"].Value 
                },
                Helper.CnnVal("LukaKomp"));

                //MessageBox.Show(cb.Value.ToString());
                dataGridView1.Rows[e.RowIndex].Cells["trenutnaPrivilegija"].Value = cb.Value.ToString();
            }
                
        }

        private void button1_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void textBox1_KeyPress(object sender, KeyPressEventArgs e)
        {
            string unos = textBox1.Text;

            for (int i = 0; i < dataGridView1.Rows.Count - 1; i++)
            {
                if (dataGridView1.Rows[i].Cells["username"].Value.ToString().ToLower().Contains(unos.ToLower()))
                {
                    dataGridView1.Rows[i].Visible = true;
                }
                else dataGridView1.Rows[i].Visible = false;
            }
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }
        private void Privilegije_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void Privilegije_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }


    }
}
