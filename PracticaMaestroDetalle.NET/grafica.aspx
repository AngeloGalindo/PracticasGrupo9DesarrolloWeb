<%@ Page Title="" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="grafica.aspx.cs" Inherits="grafica" %>

<%@ Register Assembly="Telerik.Web.UI" Namespace="Telerik.Web.UI" TagPrefix="telerik" %>

<%@ Register TagPrefix="telerik" Namespace="Telerik.Charting" Assembly="Telerik.Web.UI" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <telerik:RadChart ID="RadChart1" style="float:left" runat="server" DataSourceID="SqlDataSource1">
        <Series>
            <telerik:ChartSeries DataYColumn="cantidad" Name="cantidad"></telerik:ChartSeries>
        </Series>
    </telerik:RadChart>

    <telerik:RadHtmlChart ID="RadHtmlChart1" Width="400px" style="float:right" runat="server" DataSourceID="SqlDataSource1">
        <PlotArea>
            <Series>
                <telerik:PieSeries DataFieldY="cantidad" NameField="Producto" Name="Producto">
                    
                </telerik:PieSeries>
            </Series>
            
        </PlotArea>
    </telerik:RadHtmlChart>

    <asp:SqlDataSource runat="server" ID="SqlDataSource1" ConnectionString='<%$ ConnectionStrings:CN %>' SelectCommand="select Producto, sum(cantidad) as cantidad from Inventario group by producto"></asp:SqlDataSource>
</asp:Content>

