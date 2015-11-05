using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using ConsumeWS.ConsumirRomanos;

namespace ConsumeWS
{
    public partial class frmConsumeWS : Form
    {
        public frmConsumeWS()
        {
            InitializeComponent();
        }

        private void btnTransforma_Click(object sender, EventArgs e)
        {
            if (txtArabigo.Text.ToString().Trim().Length > 0)
            {
                ConversionRomanos romano = new ConversionRomanos();
                txtRomano.Text= romano.ResultadoRomanos(txtArabigo.Text);
            }//if validacion>0
        }
    }
}
